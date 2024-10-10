<?php declare(strict_types=1);
namespace hiperesp\tests;

final class Runner {

    const SECTION_TEST = '--TEST--';
    const SECTION_FILE = '--FILE--';
    const SECTION_EXPECT = '--EXPECT--';
    const SECTION_CLEAN = '--CLEAN--';

    const SECTIONS = [
        self::SECTION_TEST,
        self::SECTION_FILE,
        self::SECTION_EXPECT,
        self::SECTION_CLEAN
    ];
    const REQUIRED_SECTIONS = [
        [ self::SECTION_TEST ],
        [ self::SECTION_FILE ],
        [ self::SECTION_EXPECT ],
    ];
    const SECTIONS_UNIQUE = [
    ];

    private readonly string $phpt;
    public function __construct(
        private readonly string $testFileName
    ) {
        $this->phpt = __DIR__ . "/{$testFileName}";
    }

    private static array $context = [];

    private function startTest(): void {
        $context = self::$context;
        # Run test case
        \ob_start();
        try {
            $context = eval(<<<EVAL_ANON
            return (function(\$context): array {
                {$this->getSection(self::SECTION_FILE)};
                return \$context;
            })(\$context);
            EVAL_ANON);
        } catch(\ErrorException $e) {
            echo $e->getMessage();
        }
        $output = \ob_get_clean();

        # Clean up with context
        \ob_start();
        try {
            $context = eval(<<<EVAL_ANON
            return (function(\$context) {
                {$this->getSection(self::SECTION_CLEAN)};
                return \$context;
            })(\$context);
            EVAL_ANON);
        } catch(\ErrorException $e) {
            echo "Error while trying to clean up the test case: {$e->getMessage()}";
        }
        $output.= \ob_get_clean();

        self::$context = $context;

        # Compare output
        $expectedOutput = $this->getSection(self::SECTION_EXPECT);
        if($output !== $expectedOutput) {
            \ob_start();
            var_dump($output);
            $got = \trim(\ob_get_clean());

            \ob_start();
            var_dump($expectedOutput);
            $expected = \trim(\ob_get_clean());

            throw new \Exception("Output does not match expected output.\n     Expected: {$expected}\n     Got: {$got}");
        }
    }

    public function run(bool $skip): array {
        try {
            $this->parse();
        } catch(\Exception $e) {
            return [
                "status" => "error",
                "testName" => $this->getTestName(),
                "testFile" => $this->getTestFileName(),
                "message" => $e->getMessage()
            ];
        }
        if($skip) {
            return [
                "status" => "skipped",
                "testName" => $this->getTestName(),
                "testFile" => $this->getTestFileName(),
                "message" => "Test skipped."
            ];
        }

        try {
            $this->startTest();
            return [
                "status" => "success",
                "testName" => $this->getTestName(),
                "testFile" => $this->getTestFileName(),
                "message" => "Test passed."
            ];
        } catch(\Exception $e) {
            return [
                "status" => "error",
                "testName" => $this->getTestName(),
                "testFile" => $this->getTestFileName(),
                "message" => $e->getMessage()
            ];
        }
    }

    public function getTestName(): string {
        $name = "";
        if($this->hasSection(self::SECTION_TEST)) {
            $name = $this->getSection(self::SECTION_TEST);
        }
        if(!$name) {
            $name = $this->getTestFileName();
        }
        return $name;
    }

    public function getTestFileName(): string {
        return $this->testFileName;
    }

    private array $sections = [];
    private function parse(): void {
        $lines = \file($this->phpt);

        $currentSection = null;
        foreach($lines as $i => $line) {
            $line = \str_replace("\r", "", $line);
            foreach(self::SECTIONS as $sectionName) {
                if($line !== "{$sectionName}\n") continue;

                // remove last newline from last section
                if($currentSection !== null) {
                    $this->sections[$currentSection] = \substr($this->sections[$currentSection], 0, -1);
                }

                $currentSection = $sectionName;
                if($this->hasSection($currentSection)) {
                    throw new \Exception("Invalid PHPT file. Duplicate section: {$currentSection}");
                }
                $this->sections[$currentSection] = "";
                continue 2;
            }
            if($currentSection === null) {
                throw new \Exception("Invalid PHPT file. Section not found.");
            }
            $this->sections[$currentSection] .= "{$line}";
        }

        foreach(self::REQUIRED_SECTIONS as $sectionGroup) {
            if(!$this->hasSection(...$sectionGroup)) {
                throw new \Exception("Invalid PHPT file. Missing section: ".\implode(", ", $sectionGroup));
            }
        }

        foreach(self::SECTIONS_UNIQUE as $sectionGroup) {
            # must have only one of the group
            $alreadyHas = false;
            foreach($sectionGroup as $section) {
                if($this->hasSection($section)) {
                    if($alreadyHas) {
                        throw new \Exception("Invalid PHPT file. Duplicate section: ".\implode(", ", $sectionGroup));
                    }
                    $alreadyHas = true;
                }
            }
        }
    }

    private function getSection(string ...$sections): string {
        foreach($sections as $section) {
            if($this->hasSection($section)) {
                return $this->sections[$section];
            }
        }
        return "";
    }
    private function hasSection(string ...$sections): bool {
        foreach($sections as $section) {
            if(isset($this->sections[$section])) {
                return true;
            }
        }
        return false;
    }

    public static function getSuites() {
        return \array_merge([
            "all", 
        ], \array_filter(\scandir(__DIR__), function($dir) {
            return \is_dir(__DIR__ . '/' . $dir) && $dir !== "." && $dir !== "..";
        }));
    }

    public static function runSuite(string $suite): string {
        $base = __DIR__."/";

        $totalTests = \glob("{$base}*/*.phpt");
        foreach($totalTests as $i => $test) {
            $totalTests[$i] = \str_replace($base, "", $test);
        }

        if($suite==="all") {
            $tests = $totalTests;
        } else {
            $suites = self::getSuites();
            if(!\in_array($suite, $suites)) {
                throw new \Exception("Invalid test suite.");
            }
            $tests = \glob("{$base}{$suite}/*.phpt");
            foreach($tests as $i => $test) {
                $tests[$i] = \str_replace($base, "", $test);
            }
        }

        \natsort($totalTests);

        $summary = [
            "totalTests" => \count($totalTests),
            "totalSuite" => \count($tests),
            "failed" => 0,
            "passed" => 0,
            "skipped" => 0,
            "start" => \microtime(true)
        ];

        echo "===============================================================================================\n";
        echo "Running {$suite} tests:\n";
        foreach($totalTests as $test) {
            $skip = !\in_array($test, $tests);

            $runner = new self($test);
            $output = $runner->run($skip);

            $statusStr = "";
            $testNameStr = $output["testName"];
            $testFileStr = $output["testFile"];
            $messageStr = $output["message"];

            if($output["status"] === "error") {
                $summary["failed"]++;
                $statusStr = "FAIL";
            } else if($output["status"] === "success") {
                $summary["passed"]++;
                $statusStr = "PASS";
                $messageStr = "";
            } else if($output["status"] === "skipped") {
                $summary["skipped"]++;
                $statusStr = "SKIP";
                $messageStr = "";
            } else {
                $summary["failed"]++;
                $statusStr = "FAIL";
                $messageStr = "Unknown Status.";
            }

            $statusStr = \str_pad("{$statusStr}", 4, ".", \STR_PAD_RIGHT);
            $testNameStr = \str_pad("{$testNameStr} ", 50, ".", \STR_PAD_RIGHT);
            $testFileStr = \str_pad(" [{$testFileStr}]", 40, ".", \STR_PAD_LEFT);

            echo "{$statusStr} {$testNameStr}{$testFileStr}\n";
            if($messageStr) {
                echo "     {$messageStr}\n";
            }

        }

        $summary["end"] = \microtime(true);
        $summary["timeTaken"] = \number_format($summary["end"] - $summary["start"], 2);

        $percentSkipped = \str_pad(\number_format($summary["skipped"] / $summary["totalTests"] * 100, 1)."%", 6, " ", \STR_PAD_LEFT);
        $percentFailed1 = \str_pad(\number_format($summary["failed" ] / $summary["totalTests"] * 100, 1)."%", 6, " ", \STR_PAD_LEFT);
        $percentFailed2 = \str_pad(\number_format($summary["failed" ] / $summary["totalSuite"] * 100, 1)."%", 6, " ", \STR_PAD_LEFT);
        $percentPassed1 = \str_pad(\number_format($summary["passed" ] / $summary["totalTests"] * 100, 1)."%", 6, " ", \STR_PAD_LEFT);
        $percentPassed2 = \str_pad(\number_format($summary["passed" ] / $summary["totalSuite"] * 100, 1)."%", 6, " ", \STR_PAD_LEFT);

        $totalTestsStr = \str_pad((string)$summary["totalTests"], 6, " ", \STR_PAD_LEFT);
        $totalSuiteStr = \str_pad((string)$summary["totalSuite"], 6, " ", \STR_PAD_LEFT);
        $skippedStr    = \str_pad((string)$summary["skipped"   ], 6, " ", \STR_PAD_LEFT);
        $failedStr     = \str_pad((string)$summary["failed"    ], 6, " ", \STR_PAD_LEFT);
        $passedStr     = \str_pad((string)$summary["passed"    ], 6, " ", \STR_PAD_LEFT);
        $timeTakenStr  = \str_pad((string)$summary["timeTaken" ], 6, " ", \STR_PAD_LEFT);

        $output ="===============================================================================================\n";
        $output.="Number of tests : {$totalTestsStr}            {$totalSuiteStr}\n";
        $output.="Tests skipped   : {$skippedStr   } ({$percentSkipped}) --------\n";
        $output.="Tests failed    : {$failedStr    } ({$percentFailed1}) ({$percentFailed2})\n";
        $output.="Tests passed    : {$passedStr    } ({$percentPassed1}) ({$percentPassed2})\n";
        $output.="-----------------------------------------------------------------------------------------------\n";
        $output.="Time taken      : {$timeTakenStr } seconds\n";
        $output.="===============================================================================================\n";

        return $output;
    }
}