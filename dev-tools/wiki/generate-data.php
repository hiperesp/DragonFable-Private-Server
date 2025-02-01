<?php declare(strict_types=1);
\ini_set("memory_limit", "1024M");

define("ONLY_PAGES", true);

$categories = [
    "item" => [
        "generate" => true,
        "slug" => "items",
        "name" => "Items",
        "description" => "Items are objects that can be used, equipped, or consumed.",
        "mapping" => [
            "nameKey" => "name",
            "descriptionKey" => "description",
        ],
        "findRelated" => function(int $id): array {
            $related = [];

            // find all shops that sell this item
            foreach(getRelated("itemShop_item", "itemId", $id) as $itemShopItem) {
                foreach(getRelated("itemShop", "id", $itemShopItem["itemShopId"]) as $itemShop) {
                    $outputData = outputData("itemShop", $itemShop, false);
                    $related[$outputData["page"]["slug"]] = $outputData;
                }
            }

            // find all quests that give this item
            foreach(getRelated("quest_item", "itemId", $id) as $questItem) {
                foreach(getRelated("quest", "id", $questItem["questId"]) as $quest) {
                    $outputData = outputData("quest", $quest, false);
                    $related[$outputData["page"]["slug"]] = $outputData;
                }
            }

            // find all mergeshops that require this item (itemId1, itemId2)
            // find all mergeshops that give this item (itemId)
            foreach(getRelated("merge", [ "itemId1", "itemId2", "itemId"], $id) as $merge) {
                foreach(getRelated("mergeShop_merge", "mergeId", $merge["id"]) as $mergeShopMerge) {
                    foreach(getRelated("mergeShop", "id", $mergeShopMerge["mergeShopId"]) as $mergeShop) {
                        $outputData = outputData("mergeShop", $mergeShop, false, [
                            "mergeShop_merge" => $mergeShopMerge,
                            "merge" => $merge,
                        ]);
                        $related[$outputData["page"]["slug"]] = $outputData;
                    }
                }
            }

            return $related;
        },
    ],
    "mergeShop" => [
        "generate" => true,
        "slug" => "merge-shops",
        "name" => "Merge Shops",
        "description" => "Merge Shops are shops where you can merge items together to create new items.",
        "mapping" => [
            "nameKey" => "name",
            "descriptionKey" => null,
        ],
        "findRelated" => function(int $id): array {
            $related = [];

            // find all items that you can get from this mergeshop
            foreach(getRelated("mergeShop_merge", "mergeShopId", $id) as $mergeShopMerge) {
                foreach(getRelated("merge", "id", $mergeShopMerge["mergeId"]) as $merge) {
                    foreach(getRelated("item", "id", $merge["itemId"]) as $item) {
                        $outputData = outputData("item", $item, false);
                        $related[$outputData["page"]["slug"]] = $outputData;
                    }
                }
            }

            return $related;
        },
    ],
    "quest" => [
        "generate" => true,
        "slug" => "quests",
        "name" => "Quests",
        "description" => "Quests are tasks that you can complete to earn rewards.",
        "mapping" => [
            "nameKey" => "name",
            "descriptionKey" => "description",
        ],
        "findRelated" => function(int $id): array {
            $related = [];

            // find all items that you can get from this quest
            foreach(getRelated("quest_item", "questId", $id) as $questItem) {
                foreach(getRelated("item", "id", $questItem["itemId"]) as $item) {
                    $outputData = outputData("item", $item, false);
                    $related[$outputData["page"]["slug"]] = $outputData;
                }
            }

            return $related;
        },
    ],
    "itemShop" => [
        "generate" => true,
        "slug" => "item-shops",
        "name" => "Item Shops",
        "description" => "Item Shops are shops where you can buy items.",
        "mapping" => [
            "nameKey" => "name",
            "descriptionKey" => null,
        ],
        "findRelated" => function(int $id): array {
            $related = [];

            // find all items that you can buy from this item shop
            foreach(getRelated("itemShop_item", "itemShopId", $id) as $itemShopItem) {
                foreach(getRelated("item", "id", $itemShopItem["itemId"]) as $item) {
                    $outputData = outputData("item", $item, false);
                    $related[$outputData["page"]["slug"]] = $outputData;
                }
            }

            return $related;
        },
    ],
];

$preloaded = [];

foreach($categories as $categoryKey => $categoryInfo) {
    if(!$categoryInfo["generate"]) {
        continue;
    }

    echo "Generating {$categoryInfo["name"]}...\n";

    if(!\is_dir("../wiki/generated/pages/{$categoryInfo["slug"]}")) {
        \mkdir("../wiki/generated/pages/{$categoryInfo["slug"]}", 0777, true);
    }
    \array_map('unlink', \glob("../wiki/generated/pages/{$categoryInfo["slug"]}/*"));

    if(ONLY_PAGES) {
        $allFiles = \scandir("../wiki/generated/data/{$categoryKey}");

        foreach($allFiles as $key => $file) {
            if($file==="." || $file==="..") {
                continue;
            }

            $outputData = \json_decode(\file_get_contents("../wiki/generated/data/{$categoryKey}/{$file}"), true);

            $outputPage = outputPage($outputData);
            if(!\is_dir("../wiki/generated/pages/{$outputData["page"]["slugBase"]}")) {
                \mkdir("../wiki/generated/pages/{$outputData["page"]["slugBase"]}", 0777, true);
            }
            \file_put_contents("../wiki/generated/pages/{$outputData["page"]["fullSlug"]}.md", $outputPage);

            if($key%100===0) {
                echo "Generated {$outputData["page"]["fullSlug"]}\n";
            }
        }
    } else {
        $allData = preload($categoryKey);

        if(!\is_dir("../wiki/generated/data/{$categoryKey}")) {
            \mkdir("../wiki/generated/data/{$categoryKey}", 0777, true);
        }
        \array_map('unlink', \glob("../wiki/generated/data/{$categoryKey}/*"));

        foreach($allData as $key => $data) {

            $outputData = outputData($categoryKey, $data, true);
            \file_put_contents("../wiki/generated/data/{$categoryKey}/{$outputData["page"]["slug"]}.json", \json_encode($outputData, JSON_PRETTY_PRINT));

            $outputPage = outputPage($outputData);
            if(!\is_dir("../wiki/generated/pages/{$outputData["page"]["slugBase"]}")) {
                \mkdir("../wiki/generated/pages/{$outputData["page"]["slugBase"]}", 0777, true);
            }
            \file_put_contents("../wiki/generated/pages/{$outputData["page"]["fullSlug"]}.md", $outputPage);

            if($key%100===0) {
                echo "Generated {$outputData["page"]["fullSlug"]}\n";
            }
        }
    }

    $outputIndex = outputIndex($categoryKey, "../wiki/generated/data/{$categoryKey}");
    \file_put_contents("../wiki/generated/pages/{$categoryInfo["slug"]}.md", $outputIndex);
}

$outputHome = outputHome("../wiki/generated/pages");
\file_put_contents("../wiki/generated/pages/home.md", $outputHome);

function preload(string $category): array {
    global $preloaded;

    if(!isset($preloaded[$category])) {
        $preloaded[$category] = \json_decode(\file_get_contents("../download-production-data/converted/{$category}/merged.json"), true);
    }

    return $preloaded[$category];
}

function outputData(string $category, array $data, bool $getRelated, array $appendRelated = []): array {
    global $categories;

    if(!isset($categories[$category])) {
        throw new \Exception("Category definition not found: {$category}");
    }

    $categoryInfo = $categories[$category];

    $id = $data["id"];
    $name = "<no name available>";
    if(isset($data[$categoryInfo["mapping"]["nameKey"]])) {
        $name = $data[$categoryInfo["mapping"]["nameKey"]];
    }
    $description = "<no description available>";
    if($categoryInfo["mapping"]["descriptionKey"]!==null) {
        if(isset($data[$categoryInfo["mapping"]["descriptionKey"]])) {
            $description = $data[$categoryInfo["mapping"]["descriptionKey"]];
        }
    }
    list($slugBase, $slug, $fullSlug) = generateSlug($category, $id, $name);

    $related = $appendRelated;
    if($getRelated) {
        $related = $categoryInfo["findRelated"]($id);
    }

    return [
        "page" => [
            "slugBase" => $slugBase,
            "slug" => $slug,
            "fullSlug" => $fullSlug,
            "id" => $id,
            "name" => $name,
            "description" => $description
        ],
        "info" => [
            "type" => $category,
            "properties" => $data,
        ],
        "related" => $related
    ];
}

function getRelated(string $category, array|string $key, mixed $value): array {
    $related = [];
    $allData = preload($category);
    foreach($allData as $data) {
        if(!\is_array($key)) {
            $key = [$key];
        }
        foreach($key as $subKey) {
            if($data[$subKey]!==$value) {
                continue;
            }
            $related[$data["id"]] = $data;
            if($subKey==="id") {
                continue 2;
            }
        }
    }
    return \array_values($related);
}

function generateSlug(string $category, int $id, string $name): array {
    global $categories;

    $base = $categories[$category]["slug"];

    $slug = \strtolower("{$id}-{$name}");
    $slug = \preg_replace("/[^a-z0-9-]/", "-", \strtolower($slug));
    $slug = \preg_replace("/-+/", "-", $slug);
    $slug = \rtrim($slug, "-");

    $fullSlug = "{$base}/{$slug}";

    return [$base, $slug, $fullSlug];
}


function outputPage(array $data): string {
    if(\function_exists("customOutput{$data["info"]["type"]}")) {
        return \call_user_func("customOutput{$data["info"]["type"]}", $data);
    }

    $output = "# {$data["page"]["name"]}\n\n";
    $output .= "{$data["page"]["description"]}\n\n";

    $output .= "## Properties\n\n";
    $output .= "```json\n";
    $output .= \json_encode($data["info"]["properties"], JSON_PRETTY_PRINT);
    $output .= "\n```\n\n";

    if(\count($data["related"])>0) {
        $output .= "## Related\n\n";
        foreach($data["related"] as $relatedData) {
            $name = $relatedData["page"]["name"];
            if(!$name) {
                $name = "<no name>";
            }
            $output .= "- [{$name}](../{$relatedData["page"]["fullSlug"]}.md)\n";
        }
        $output .= "\n";
    }

    return $output;
}

function outputIndex(string $category, string $dataDir): string {
    $output = "# {$category}\n\n";

    #scan dir to create links
    $files = \scandir($dataDir);

    $output .= "## Items\n\n";
    foreach($files as $file) {
        if($file==="." || $file==="..") {
            continue;
        }
        $data = \json_decode(\file_get_contents("{$dataDir}/{$file}"), true);
        $output .= "- [{$data["page"]["name"]}]({$data["page"]["fullSlug"]})\n";
    }

    return $output;
}

function outputHome(string $pagesDir): string {
    global $categories;
    $output = "# Home\n\n";

    #scan dir to create links
    $files = \scandir($pagesDir);

    $output .= "## Categories\n\n";
    foreach($files as $file) {
        if($file==="." || $file==="..") {
            continue;
        }
        if(\is_dir("{$pagesDir}/{$file}")) {
            continue;
        }

        $name = null;
        foreach($categories as $categoryInfo) {
            if("{$categoryInfo["slug"]}.md"!==$file) {
                continue;
            }
            $name = $categoryInfo["name"];
            break;
        }
        if($name===null) {
            continue;
        }
        $output .= "- [{$name}]({$file})\n";
    }

    return $output;
}

function customOutputItem(array $data): string {

    $output = "# {$data["page"]["name"]}\n\n";
    $output .= "{$data["page"]["description"]}\n\n";

    $groups = [
        "Basic Information" => [
            "id" => "ID",
            "name" => "Name",
            "description" => "Description",
        ],
        "Properties" => [
            "visible" => "Visible",
            "destroyable" => "Destroyable",
            "sellable" => "Sellable",
            "dragonAmulet" => "Dragon Amulet Required",
            "currency" => "Currency",
            "cost" => "Cost",
            "maxStackSize" => "Max Stack Size",
            "bonus" => "Bonus",
            "rarity" => "Rarity",
            "level" => "Level Requirement",
        ],
        "Attributes" => [
            "type" => "Type",
            "element" => "Element",
            "categoryId" => "Category",
            "equipSpot" => "Equip Spot",
            "itemType" => "Item Type",
        ],
        "Stats" => [
            "strength" => "Strength",
            "dexterity" => "Dexterity",
            "intelligence" => "Intelligence",
            "luck" => "Luck",
            "charisma" => "Charisma",
            "endurance" => "Endurance",
            "wisdom" => "Wisdom",
        ],
        "Combat Values" => [
            "damageMin" => "Min Damage",
            "damageMax" => "Max Damage",
            "defenseMelee" => "Defense (Melee)",
            "defensePierce" => "Defense (Pierce)",
            "defenseMagic" => "Defense (Magic)",
            "critical" => "Critical",
            "parry" => "Parry",
            "dodge" => "Dodge",
            "block" => "Block",
            "resists" => "Resistances",
        ],
        "Assets" => [
            "swf" => "SWF File",
            "icon" => "Icon",
        ],
    ];

    foreach ($groups as $category => $fields) {
        $output .= "### " . \htmlspecialchars($category) . "\n\n";

        foreach ($fields as $key => $label) {
            if (!isset($data["info"]["properties"]) || !\array_key_exists($key, $data["info"]["properties"])) {
                continue;
            }
            $label = \htmlspecialchars($label);
            $value = \htmlspecialchars((string)$data["info"]["properties"][$key]);
            $output .= "- **{$label}**: {$value}\n";
        }

        $output .= "\n";
    }

    if(\count($data["related"])>0) {
        $output .= "## Related\n\n";

        $itemShopsSelling = [];
        $questsReward = [];
        $mergeShopsGive = [];
        $mergeShopsRequire = [];

        foreach($data["related"] as $relatedData) {
            switch($relatedData["info"]["type"]) {
                case "itemShop":
                    $itemShopsSelling[] = $relatedData;
                    break;
                case "quest":
                    $questsReward[] = $relatedData;
                    break;
                case "mergeShop":
                    if($relatedData["related"]["merge"]["itemId"]==$data["info"]["properties"]["id"]) {
                        $mergeShopsGive[] = $relatedData;
                    } else {
                        $mergeShopsRequire[] = $relatedData;
                    }
                    break;
            }
        }

        $groups = [
            "Item Shops Selling" => $itemShopsSelling,
            "Quests Rewarding" => $questsReward,
            "Merge Shops Giving" => $mergeShopsGive,
            "Merge Shops Requiring" => $mergeShopsRequire,
        ];

        foreach($groups as $category => $relatedItems) {
            if(\count($relatedItems)===0) {
                continue;
            }

            $output .= "### " . \htmlspecialchars($category) . "\n\n";

            foreach($relatedItems as $relatedData) {
                $name = $relatedData["page"]["name"];
                if(!$name) {
                    $name = "<no name>";
                }
                $output .= "- [{$name}](../{$relatedData["page"]["fullSlug"]}.md)\n";
            }

            $output .= "\n";
        }
    }

    return $output;
}