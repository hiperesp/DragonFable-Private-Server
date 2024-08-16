<?php
namespace hiperesp\server\vo;

class CharacterVO extends ValueObject {

    public readonly int $id;
    public readonly int $userId;

    public readonly string $name;
    public readonly string $gender;
    public readonly string $pronoun;

    public readonly int $hairId;
    public readonly int $raceId;
    public readonly int $classId;

    public readonly string $colorHair;
    public readonly string $colorSkin;
    public readonly string $colorBase;
    public readonly string $colorTrim;

    public readonly int $level;
    public readonly int $accessLevel;
    public readonly bool $hasDragonAmulet;

    public function __construct(array $data) {

        $this->id = $data['id'];
        $this->userId = $data['userId'];

        $this->name = $data['name'];
        $this->gender = $data['gender'];
        $this->pronoun = $data['pronoun'];

        $this->hairId = $data['hairId'];
        $this->raceId = $data['raceId'];
        $this->classId = $data['classId'];

        $this->colorHair = $data['colorHair'];
        $this->colorSkin = $data['colorSkin'];
        $this->colorBase = $data['colorBase'];
        $this->colorTrim = $data['colorTrim'];

        $this->level = $data['level'];
        $this->accessLevel = $data['accessLevel'];
        $this->hasDragonAmulet = $data['hasDragonAmulet'] != '0' ? true : false;
    }

    public function asCreatedResponse(): array {
        return [
            "code" => 0,
            "reason" => "Character created Successfully!",
            "message" => "none",
            "action" => "none"
        ];
    }

    public function asDeleteResponse(): \SimpleXMLElement {
        $xml = new \SimpleXMLElement('<charDelete/>');
        $charDelete = $xml->addChild('charDelete');
        $charDelete->addAttribute('message', 'Character Deleteion Successful!!');

        return $xml;
    }

    public function asDragonAmuletCheckResponse(): \SimpleXMLElement {
        $xml = new \SimpleXMLElement('<character/>');
        $character = $xml->addChild('character');
        $character->addAttribute('intDragonAmulet', $this->hasDragonAmulet ? '1' : '0');

        return $xml;
    }

}
