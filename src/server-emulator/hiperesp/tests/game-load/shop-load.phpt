--TEST--
Load shop
--FILE--

$itemShopController = new \hiperesp\server\controllers\ItemShopController;

$response = $itemShopController->load(new \SimpleXMLElement(<<<XML
<flash><intShopID>12</intShopID></flash>
XML));

$response = \json_decode(\json_encode($response), true);

print_r($response);

--EXPECT--
Array
(
    [shop] => Array
        (
            [@attributes] => Array
                (
                    [ShopID] => 12
                    [strCharacterName] => Warrior Shop
                    [intCount] => -100
                )

            [items] => Array
                (
                    [0] => Array
                        (
                            [@attributes] => Array
                                (
                                    [ItemID] => 474
                                    [strItemName] => Bronze Blade 
                                    [strItemDescription] => This Bronze Blade was forged centuries ago by a noble swordsman, bent on destroying evil. 
                                    [bitVisible] => 1
                                    [bitDestroyable] => 1
                                    [bitSellable] => 1
                                    [bitDragonAmulet] => 0
                                    [intCurrency] => 2
                                    [intCost] => 15
                                    [intMaxStackSize] => 1
                                    [intBonus] => 0
                                    [intRarity] => 1
                                    [intLevel] => 1
                                    [strType] => Melee
                                    [strElement] => Metal
                                    [strCategory] => Weapon
                                    [strEquipSpot] => Weapon
                                    [strItemType] => Sword
                                    [strFileName] => items/swords/sword-lockwood1.swf
                                    [strIcon] => sword
                                    [intStr] => 0
                                    [intDex] => 0
                                    [intInt] => 0
                                    [intLuk] => 0
                                    [intCha] => 0
                                    [intEnd] => 0
                                    [intWis] => 0
                                    [intMin] => 4
                                    [intMax] => 9
                                    [intDefMelee] => 0
                                    [intDefPierce] => 0
                                    [intDefMagic] => 0
                                    [intCrit] => 0
                                    [intParry] => 0
                                    [intDodge] => 0
                                    [intBlock] => 0
                                    [strResists] => 
                                )

                        )

                    [1] => Array
                        (
                            [@attributes] => Array
                                (
                                    [ItemID] => 475
                                    [strItemName] => Mirrored Edge
                                    [strItemDescription] => This sword shines like a bright mirror...revealing not only your reflection, but that of your enemies.
                                    [bitVisible] => 1
                                    [bitDestroyable] => 1
                                    [bitSellable] => 1
                                    [bitDragonAmulet] => 0
                                    [intCurrency] => 2
                                    [intCost] => 26
                                    [intMaxStackSize] => 1
                                    [intBonus] => 0
                                    [intRarity] => 1
                                    [intLevel] => 2
                                    [strType] => Melee
                                    [strElement] => Metal
                                    [strCategory] => Weapon
                                    [strEquipSpot] => Weapon
                                    [strItemType] => Sword
                                    [strFileName] => items/swords/sword-lockwood2.swf
                                    [strIcon] => sword
                                    [intStr] => 0
                                    [intDex] => 0
                                    [intInt] => 0
                                    [intLuk] => 0
                                    [intCha] => 0
                                    [intEnd] => 0
                                    [intWis] => 0
                                    [intMin] => 8
                                    [intMax] => 12
                                    [intDefMelee] => 0
                                    [intDefPierce] => 0
                                    [intDefMagic] => 0
                                    [intCrit] => 0
                                    [intParry] => 0
                                    [intDodge] => 0
                                    [intBlock] => 0
                                    [strResists] => 
                                )

                        )

                    [2] => Array
                        (
                            [@attributes] => Array
                                (
                                    [ItemID] => 476
                                    [strItemName] => Red Eyed Gloom
                                    [strItemDescription] => This sword seems gloomy, so gloomy that you swear those are red eyes on the cross piece.
                                    [bitVisible] => 1
                                    [bitDestroyable] => 1
                                    [bitSellable] => 1
                                    [bitDragonAmulet] => 0
                                    [intCurrency] => 2
                                    [intCost] => 50
                                    [intMaxStackSize] => 1
                                    [intBonus] => 0
                                    [intRarity] => 1
                                    [intLevel] => 3
                                    [strType] => Melee
                                    [strElement] => Metal
                                    [strCategory] => Weapon
                                    [strEquipSpot] => Weapon
                                    [strItemType] => Sword
                                    [strFileName] => items/swords/sword-lockwood3.swf
                                    [strIcon] => sword
                                    [intStr] => 0
                                    [intDex] => 0
                                    [intInt] => 0
                                    [intLuk] => 0
                                    [intCha] => 0
                                    [intEnd] => 0
                                    [intWis] => 0
                                    [intMin] => 10
                                    [intMax] => 16
                                    [intDefMelee] => 0
                                    [intDefPierce] => 0
                                    [intDefMagic] => 0
                                    [intCrit] => 0
                                    [intParry] => 0
                                    [intDodge] => 0
                                    [intBlock] => 0
                                    [strResists] => 
                                )

                        )

                    [3] => Array
                        (
                            [@attributes] => Array
                                (
                                    [ItemID] => 477
                                    [strItemName] => Knights Glaive
                                    [strItemDescription] => Whenever you swing this glaive, its song tells the tales of those knights of ancient times.
                                    [bitVisible] => 1
                                    [bitDestroyable] => 1
                                    [bitSellable] => 1
                                    [bitDragonAmulet] => 0
                                    [intCurrency] => 2
                                    [intCost] => 75
                                    [intMaxStackSize] => 1
                                    [intBonus] => 0
                                    [intRarity] => 1
                                    [intLevel] => 4
                                    [strType] => Melee
                                    [strElement] => Metal
                                    [strCategory] => Weapon
                                    [strEquipSpot] => Weapon
                                    [strItemType] => Sword
                                    [strFileName] => items/swords/sword-lockwood4.swf
                                    [strIcon] => sword
                                    [intStr] => 0
                                    [intDex] => 0
                                    [intInt] => 0
                                    [intLuk] => 0
                                    [intCha] => 0
                                    [intEnd] => 0
                                    [intWis] => 0
                                    [intMin] => 10
                                    [intMax] => 20
                                    [intDefMelee] => 0
                                    [intDefPierce] => 0
                                    [intDefMagic] => 0
                                    [intCrit] => 0
                                    [intParry] => 0
                                    [intDodge] => 0
                                    [intBlock] => 0
                                    [strResists] => 
                                )

                        )

                    [4] => Array
                        (
                            [@attributes] => Array
                                (
                                    [ItemID] => 478
                                    [strItemName] => Legendary Magma Sword
                                    [strItemDescription] => An ancient blade forged in the pits of a volcano, that has been destroyed and forgotten. 
                                    [bitVisible] => 1
                                    [bitDestroyable] => 1
                                    [bitSellable] => 1
                                    [bitDragonAmulet] => 0
                                    [intCurrency] => 2
                                    [intCost] => 150
                                    [intMaxStackSize] => 1
                                    [intBonus] => 0
                                    [intRarity] => 1
                                    [intLevel] => 5
                                    [strType] => Melee
                                    [strElement] => Fire
                                    [strCategory] => Weapon
                                    [strEquipSpot] => Weapon
                                    [strItemType] => Sword
                                    [strFileName] => items/swords/sword-lockwood5.swf
                                    [strIcon] => sword
                                    [intStr] => 0
                                    [intDex] => 0
                                    [intInt] => 0
                                    [intLuk] => 0
                                    [intCha] => 0
                                    [intEnd] => 0
                                    [intWis] => 0
                                    [intMin] => 15
                                    [intMax] => 21
                                    [intDefMelee] => 0
                                    [intDefPierce] => 0
                                    [intDefMagic] => 0
                                    [intCrit] => 0
                                    [intParry] => 0
                                    [intDodge] => 0
                                    [intBlock] => 0
                                    [strResists] => 
                                )

                        )

                )

        )

)
