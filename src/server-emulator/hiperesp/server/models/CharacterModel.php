<?php
namespace hiperesp\server\models;

use hiperesp\server\exceptions\DFException;
use hiperesp\server\vo\CharacterVO;
use hiperesp\server\vo\ItemVO;
use hiperesp\server\vo\QuestVO;
use hiperesp\server\vo\SettingsVO;
use hiperesp\server\vo\UserVO;

class CharacterModel extends Model {

    const COLLECTION = 'char';

    /** @return array<CharacterVO> */
    public function getByUser(UserVO $user): array {
        $chars = $this->storage->select(self::COLLECTION, ['userID' => $user->id], null);
        return \array_map(fn($char) => new CharacterVO($char), $chars);
    }

    public function getByUserAndId(UserVO $user, int $id): CharacterVO {
        $char = $this->storage->select(self::COLLECTION, ['userID' => $user->id, 'id' => $id]);
        if(isset($char[0]) && $char = $char[0]) {
            $char = new CharacterVO($char);
            $this->updateLastTimeSeen($char);
            return $char;
        }
        throw new DFException(DFException::CHARACTER_NOT_FOUND);
    }

    public function create(UserVO $user, array $input): CharacterVO {
        $data['userId'] = $user->id;
        $data['name'] = $input['strCharacterName'];
        $data['gender'] = $input['strGender'];
        $data['pronoun'] = $input['strPronoun'];
        $data['hairId'] = $input['intHairID'];
        $data['colorHair'] = \dechex($input['intColorHair']);
        $data['colorSkin'] = \dechex($input['intColorSkin']);
        $data['colorBase'] = \dechex($input['intColorBase']);
        $data['colorTrim'] = \dechex($input['intColorTrim']);
        $data['classId'] = $input['intClassID'];
        $data['baseClassId'] = $input['intClassID'];
        $data['raceId'] = '1';
        if($user->upgraded) {
            $data['dragonAmulet'] = 1;
        }

        $char = $this->storage->insert(self::COLLECTION, $data);

        return new CharacterVO($char);
    }

    public function buyItem(CharacterVO $char, ItemVO $item): void {
        
    }

    public function delete(CharacterVO $char): void {
        $this->storage->delete(self::COLLECTION, ['id' => $char->id]);
    }

    public function changeHomeTown(CharacterVO $char, QuestVO $town): void {
        $this->storage->update(self::COLLECTION, [
            'id' => $char->id,
            'questId' => $town->id
        ]);
    }

    public function setQuestString(CharacterVO $char, int $index, int $value): void {
        $questString = $char->quests;
        $questString[$index] = $value;
        $this->storage->update(self::COLLECTION, [
            'id' => $char->id,
            'quests' => $questString
        ]);
    }

    public function applyQuestRewards(SettingsVO $settings, CharacterVO $char, QuestVO $quest, array $reward): void {
        if($quest->isDailyQuest()) {
            if($char->getDailyQuestAvailable()) {
                $this->setDailyQuestDone($char);
                $reward['coins'] = $settings->dailyQuestCoinsReward;
            }
        }
        $this->applyExpSave($settings, $char, $quest, $reward);
    }

    public function applyExpSave(SettingsVO $settings, CharacterVO $char, QuestVO $quest, array $reward): void {
        $experience = $char->experience;
        $gems = $char->gems;
        $gold = $char->gold;
        $silver = $char->silver;
        $level = $char->level;
        $coins = $char->coins;
        $experienceToLevel = $char->experienceToLevel;

        if(isset($reward['experience'])) {
            $experience += \min($reward['experience'], $quest->maxExp);
        }
        if(isset($reward['gems'])) {
            $gems += \min($reward['gems'], $quest->maxGems);
        }
        if(isset($reward['gold'])) {
            $gold += \min($reward['gold'], $quest->maxGold);
        }
        if(isset($reward['silver'])) {
            $silver += \min($reward['silver'], $quest->maxSilver);
        }
        if(isset($reward['coins'])) {
            $coins += $reward['coins']; // no max coins is defined in the quest
        }

        if($settings->levelUpMultipleTimes) {
            // if player gets more experience than needed to level up, will level up multiple times
            while($experience >= $experienceToLevel) {
                $experience -= $experienceToLevel;
                $level++;
                $experienceToLevel = $this->calcExperienceToLevelUp($char->level + 1) - $experience;
            }
        } else {
            // if player gets more experience than needed to level up, will level up only once
            if($experience >= $experienceToLevel) {
                $experience = 0;
                $level++;
                $experienceToLevel = $this->calcExperienceToLevelUp($char->level + 1);
            }
        }

        $this->applyLevelUpBonuses($char, $level);

        $this->storage->update(self::COLLECTION, [
            'id' => $char->id,
            'experience' => $experience,
            'gems' => $gems,
            'gold' => $gold,
            'silver' => $silver,
            'level' => $level,
            'coins' => $coins,
            'experienceToLevel' => $experienceToLevel
        ]);
    }

    private function applyLevelUpBonuses(CharacterVO $char, int $newLevel): void {
        $bonusesPerLevel = [
            'hitPoints' => 20,
            'manaPoints' => 5,
            'statPoints' => 5
        ];

        $fullBonuses = [
            'hitPoints'  => $char->hitPoints,
            'manaPoints' => $char->manaPoints,
            'statPoints' => $char->statPoints,
        ];
        for($i = $char->level + 1; $i <= $newLevel; $i++) {
            foreach($bonusesPerLevel as $key => $value) {
                $fullBonuses[$key] += $value;
            }
        }

        $this->storage->update(self::COLLECTION, \array_merge([ 'id' => $char->id, ], $fullBonuses));
    }

    private function calcExperienceToLevelUp(int $level): int { // need to be tested and verified
        $cap = [
            10 => \pow(2, $level) * 10,
            60 => \pow($level, 2) * 90,
            70 => (24200 * $level) - 1032000,
            80 => $level + 2000000,
            "default" => 999999999
        ];
        foreach($cap as $lvl => $exp) {
            if($level <= $lvl) {
                return $exp;
            }
        }
        return $cap['default'];
    }

    private function updateLastTimeSeen(CharacterVO $char): void {
        $this->storage->update(self::COLLECTION, [
            'id' => $char->id,
            'lastTimeSeen' => \date('Y-m-d H:i:00')
        ]);
    }

    private function setDailyQuestDone(CharacterVO $char): void {
        $this->storage->update(self::COLLECTION, [
            'id' => $char->id,
            'lastDailyQuestDone' => \date('Y-m-d')
        ]);
    }

    public function getOnlineCount(int $minutesToConsiderOnline): int {
        $times = [];
        for($i = 0; $i < $minutesToConsiderOnline; $i++) {
            $times[] = \date('Y-m-d H:i:00', \strtotime("-{$i} minutes"));
        }
        return \count($this->storage->select(self::COLLECTION, ['lastTimeSeen' => $times], null));
    }

}