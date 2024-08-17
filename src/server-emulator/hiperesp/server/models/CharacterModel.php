<?php
namespace hiperesp\server\models;

use hiperesp\server\exceptions\DFException;
use hiperesp\server\vo\CharacterVO;
use hiperesp\server\vo\QuestVO;
use hiperesp\server\vo\UserVO;

class CharacterModel extends Model {

    const COLLECTION = 'char';

    /** @return array<CharacterVO> */
    public function getByUser(UserVO $user): array {
        $characters = $this->storage->select(self::COLLECTION, ['userID' => $user->id], null);
        foreach($characters as $key => $character) {
            $characters[$key] = new CharacterVO($character);
        }
        return $characters;
    }

    public function getByUserAndId(UserVO $user, int $id): CharacterVO {
        $character = $this->storage->select(self::COLLECTION, ['userID' => $user->id, 'id' => $id]);
        if(isset($character[0]) && $character = $character[0]) {
            return new CharacterVO($character);
        }
        throw DFException::fromCode(DFException::CHARACTER_NOT_FOUND);
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
        $data['raceId'] = '1';

        $character = $this->storage->insert(self::COLLECTION, $data);

        return new CharacterVO($character);
    }

    public function delete(CharacterVO $character): void {
        $this->storage->delete(self::COLLECTION, ['id' => $character->id]);
    }

    public function changeHomeTown(CharacterVO $character, QuestVO $town): void {
        $this->storage->update(self::COLLECTION, ['id' => $character->id], ['questId' => $town->id]);
    }

}