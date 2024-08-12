<?php
namespace hiperesp\server\vo;

//#[VO(dbTable: 'characters')]
class CharacterVO extends ValueObject {

    //#[Field(cardinality: 'primary')]
    private int $id;

    //#[Field(cardinality: 'foreign', foreignVO: 'UserVO')]
    private int $userId;

    private string $name;

    private string $gender;
    private string $pronoun;

    private int $hairId;
    private int $raceId;
    private int $classId;

    private int $colorHair;
    private int $colorSkin;
    private int $colorBase;
    private int $colorTrim;

    private int $level;
    private int $accessLevel;
    private int $dragonAmulet;

}
