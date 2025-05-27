<?php declare(strict_types=1);
namespace hiperesp\server\models;

use hiperesp\server\attributes\Inject;
use hiperesp\server\exceptions\DFException;
use hiperesp\server\interfaces\Purchasable;
use hiperesp\server\vo\CharacterItemVO;
use hiperesp\server\vo\CharacterVO;
use hiperesp\server\vo\ClassVO;
use hiperesp\server\vo\HairVO;
use hiperesp\server\vo\QuestVO;
use hiperesp\server\vo\SettingsVO;
use hiperesp\server\vo\UserVO;

class CharacterModel extends Model {

    const COLLECTION = 'char';

    #[Inject] private SettingsVO $settings;

    public function refresh(CharacterVO $char): CharacterVO {
        return $this->getById($char->id);
    }

    public function getById(int $charId): CharacterVO {
        $char = $this->storage->select(self::COLLECTION, ['id' => $charId]);
        if(isset($char[0]) && $char = $char[0]) {
            return new CharacterVO($char);
        }
        throw new DFException(DFException::CHARACTER_NOT_FOUND);
    }

    /** @return array<CharacterVO> */
    public function getByUser(UserVO $user): array {
        $chars = $this->storage->select(self::COLLECTION, ['userId' => $user->id], null);
        return \array_map(fn($char) => new CharacterVO($char), $chars);
    }

    public function getByUserAndId(UserVO $user, int $id): CharacterVO {
        $char = $this->storage->select(self::COLLECTION, ['userId' => $user->id, 'id' => $id]);
        if(isset($char[0]) && $char = $char[0]) {
            $char = new CharacterVO($char);
            $this->updateLastTimeSeen($char);
            return $char;
        }
        throw new DFException(DFException::CHARACTER_NOT_FOUND);
    }

    public function getByCharItem(CharacterItemVO $charItem): CharacterVO {
        return $this->getById($charItem->charId);
    }

    public function create(UserVO $user, array $input): CharacterVO {
        $data['userId'] = $user->id;
        $data['name'] = $input['strCharacterName'];
        $data['gender'] = $input['strGender'];
        $data['pronoun'] = $input['strPronoun'];
        $data['hairId'] = $input['intHairID'];
        $data['colorHair'] = \dechex((int)$input['intColorHair']);
        $data['colorSkin'] = \dechex((int)$input['intColorSkin']);
        $data['colorBase'] = \dechex((int)$input['intColorBase']);
        $data['colorTrim'] = \dechex((int)$input['intColorTrim']);
        $data['classId'] = $input['intClassID'];
        $data['baseClassId'] = $input['intClassID'];
        $data['raceId'] = '1';
        if($user->upgraded) {
            $data['dragonAmulet'] = 1;
        }

        $char = $this->storage->insert(self::COLLECTION, $data);

        return new CharacterVO($char);
    }

    public function charge(CharacterVO $char, Purchasable $item): void {
        $this->storage->update(self::COLLECTION, [
            'id' => $char->id,
            'gold' => $char->gold - $item->getPriceGold(),
            'coins' => $char->coins - $item->getPriceCoins()
        ]);
    }

    public function refundItem(CharacterVO $char, CharacterItemVO $charItem, int $quantity, int $returnPercent): void {
        $item = $charItem->getItem();
        if($quantity > $charItem->count) {
            throw new DFException(DFException::ITEM_NOT_ENOUGH);
        }

        $returnPercent = \min(100, \max(0, $returnPercent));
        $returnProportion = $returnPercent / 100;

        $this->storage->update(self::COLLECTION, [
            'id' => $char->id,
            'gold' => \ceil($char->gold + $item->getPriceGold() * $returnProportion),
            'coins' => \ceil($char->coins + $item->getPriceCoins() * $returnProportion)
        ]);
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
        $questString[$index] = strtoupper(dechex($value));
        $this->storage->update(self::COLLECTION, [
            'id' => $char->id,
            'quests' => $questString
        ]);
    }

    public function setSkillString(CharacterVO $char, int $index, int $value): void {
        $skillString = $char->skills;
        $skillString[$index] = $value;
        $this->storage->update(self::COLLECTION, [
            'id' => $char->id,
            'skills' => $skillString
        ]);
    }

    public function applyQuestRewards(CharacterVO $char, QuestVO $quest, array $reward): void {
        if($quest->isDailyQuest()) {
            if($char->isDailyQuestAvailable()) {
                $this->setDailyQuestDone($char);
                $reward['coins'] = $this->settings->dailyQuestCoinsReward;
            }
        }
        $this->applyExpSave($char, $quest, $reward);
    }

    public function applyExpSave(CharacterVO $char, QuestVO $quest, array $reward): void {
        $experience = $char->experience;
        $gems = $char->gems;
        $gold = $char->gold;
        $silver = $char->silver;
        $level = $char->level;
        $coins = $char->coins;

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

        // if player gets more experience than needed to level up, will level up only once
        if($experience >= $char->experienceToLevel) {
            $experience = 0;
            $level++;
        }

        $this->storage->update(self::COLLECTION, [
            'id' => $char->id,
            'experience' => $experience,
            'gems' => $gems,
            'gold' => $gold,
            'silver' => $silver,
            'level' => $level,
            'coins' => $coins,
        ]);
    }

    public function trainStats(CharacterVO $char, int $wisdom, int $charisma, int $luck, int $endurance, int $dexterity, int $intelligence, int $strength, int $goldCost): void {
        $this->storage->update(self::COLLECTION, [
            'id' => $char->id,
            'wisdom' => $char->wisdom + $wisdom,
            'charisma' => $char->charisma + $charisma,
            'luck' => $char->luck + $luck,
            'endurance' => $char->endurance + $endurance,
            'dexterity' => $char->dexterity + $dexterity,
            'intelligence' => $char->intelligence + $intelligence,
            'strength' => $char->strength + $strength,
            'gold' => $char->gold - $goldCost,
        ]);
    }

    public function untrainStats(CharacterVO $char, int $goldCost): void {
        $this->storage->update(self::COLLECTION, [
            'id' => $char->id,
            'wisdom' => 0,
            'charisma' => 0,
            'luck' => 0,
            'endurance' => 0,
            'dexterity' => 0,
            'intelligence' => 0,
            'strength' => 0,
            'gold' => $char->gold - $goldCost,
        ]);
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

    public function getOnlineCount(): int {
        $minutesToConsiderOnline = $this->settings->onlineThreshold;
        $times = [];
        for($i = 0; $i < $minutesToConsiderOnline; $i++) {
            $times[] = \date('Y-m-d H:i:00', \strtotime("-{$i} minutes"));
        }
        return \count($this->storage->select(self::COLLECTION, ['lastTimeSeen' => $times], null));
    }

    public function chargeCoins(CharacterVO $char, int $coins): void {
        $this->storage->update(self::COLLECTION, [
            'id' => $char->id,
            'coins' => $char->coins - $coins
        ]);
    }

    public function changeGender(CharacterVO $char, string $gender): void {
        $this->storage->update(self::COLLECTION, [
            'id' => $char->id,
            'gender' => $gender
        ]);
    }

    public function changeName(CharacterVO $char, string $name): void {
        $this->storage->update(self::COLLECTION, [
            'id' => $char->id,
            'name' => $name
        ]);
    }

    public function changeClass(CharacterVO $char, ClassVO $class): void {
        $this->storage->update(self::COLLECTION, [
            'id' => $char->id,
            'classId' => $class->id
        ]);
    }

    public function changePronoun(CharacterVO $char, string $pronoun): void {
        $this->storage->update(self::COLLECTION, [
            'id' => $char->id,
            'pronoun' => $pronoun
        ]);
    }

    public function applyHair(CharacterVO $char, HairVO $hair, int $hairColor, int $skinColor): void {
        $this->storage->update(self::COLLECTION, [
            'id' => $char->id,
            'hairId' => $hair->id,
            'colorHair' => \dechex($hairColor),
            'colorSkin' => \dechex($skinColor)
        ]);
    }

}
