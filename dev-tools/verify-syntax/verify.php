<?php

class PHPSyntaxVerifier {
    private $root;
    private $startTime;
    private $errors = [];
    private $checkedFiles = 0;
    private $totalFiles = 0;

    public function __construct() {
        $this->root = __DIR__ . '/../../';
        $this->startTime = microtime(true);
    }

    public function run() {
        $this->printHeader();
        $this->scanFiles();
        $this->printSummary();
    }

    private function printHeader() {
        $this->printLine("═══════════════════════════════════════════════════════════════", "cyan");
        $this->printLine("    🔍  DragonFable Private Server - PHP Syntax Checker", "cyan");
        $this->printLine("═══════════════════════════════════════════════════════════════", "cyan");
        $this->printLine("");
        $this->printLine("📁  Scanning directory: " . realpath($this->root), "blue");
        $this->printLine("⏰  Started at: " . date('Y-m-d H:i:s'), "blue");
        $this->printLine("");
    }

    private function scanFiles() {
        $files = $this->findPHPFiles();
        $this->totalFiles = count($files);

        $this->printLine("📊  Found {$this->totalFiles} PHP files to check", "yellow");
        $this->printLine("");

        foreach ($files as $file) {
            $this->checkFile($file);
            $this->checkedFiles++;
            $this->printProgress();
        }

        echo "\n";
    }

    private function findPHPFiles() {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->root, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        $files = [];
        foreach ($iterator as $file) {
            if ($file->getExtension() === 'php') {
                $files[] = $file->getPathname();
            }
        }

        return $files;
    }

    private function checkFile($file) {
        $output = [];
        $returnVar = 0;
        exec("php -l " . escapeshellarg($file) . " 2>&1", $output, $returnVar);

        if ($returnVar !== 0) {
            $this->errors[] = [
                'file' => $file,
                'output' => $output
            ];
            $this->printLine("❌  " . $this->getRelativePath($file), "red");
        } else {
            $this->printLine("✅  " . $this->getRelativePath($file), "green");
        }
    }

    private function printProgress() {
        $percentage = round(($this->checkedFiles / $this->totalFiles) * 100, 1);
        $bar = $this->createProgressBar($percentage);

        echo "\r🔄  Progress: $bar {$percentage}% ({$this->checkedFiles}/{$this->totalFiles})";

        if ($this->checkedFiles < $this->totalFiles) {
            // Keep the cursor on the same line for updates
            return;
        }
    }

    private function createProgressBar($percentage) {
        $barLength = 30;
        $filled = round(($percentage / 100) * $barLength);
        $empty = $barLength - $filled;

        return "[" . str_repeat("█", $filled) . str_repeat("░", $empty) . "]";
    }

    private function printSummary() {
        $elapsed = round(microtime(true) - $this->startTime, 2);
        $errorCount = count($this->errors);
        $successCount = $this->totalFiles - $errorCount;

        $this->printLine("", "");
        $this->printLine("═══════════════════════════════════════════════════════════════", "cyan");
        $this->printLine("                            📋 SUMMARY REPORT", "cyan");
        $this->printLine("═══════════════════════════════════════════════════════════════", "cyan");
        $this->printLine("");

        $this->printLine("📈  Statistics:", "yellow");
        $this->printLine("   • Total files checked: {$this->totalFiles}", "white");
        $this->printLine("   • ✅  Files with valid syntax: {$successCount}", "green");
        $this->printLine("   • ❌  Files with syntax errors: {$errorCount}", $errorCount > 0 ? "red" : "green");
        $this->printLine("   • ⏱️  Execution time: {$elapsed}s", "blue");
        $this->printLine("");

        if ($errorCount > 0) {
            $this->printLine("🚨  SYNTAX ERRORS FOUND:", "red");
            $this->printLine("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━", "red");

            foreach ($this->errors as $error) {
                $this->printLine("");
                $this->printLine("📄  File: " . $this->getRelativePath($error['file']), "yellow");
                $this->printLine("   └─ Error:", "red");

                foreach ($error['output'] as $line) {
                    if (trim($line)) {
                        $this->printLine("      " . trim($line), "red");
                    }
                }
            }

            $this->printLine("");
            $this->printLine("🔧  Please fix the syntax errors above before proceeding.", "red");
            $this->printLine("");
            exit(1);
        } else {
            $this->printLine("🎉  All PHP files have valid syntax!", "green");
            $this->printLine("✨  Your code is ready to go!", "green");
            $this->printLine("");
        }
    }

    private function getRelativePath($file) {
        return str_replace(realpath($this->root) . DIRECTORY_SEPARATOR, '', realpath($file));
    }

    private function printLine($text, $color = "white") {
        $colors = [
            'red' => "\033[31m",
            'green' => "\033[32m",
            'yellow' => "\033[33m",
            'blue' => "\033[34m",
            'cyan' => "\033[36m",
            'white' => "\033[37m",
            'reset' => "\033[0m"
        ];

        $colorCode = $colors[$color] ?? $colors['white'];
        echo $colorCode . $text . $colors['reset'] . "\n";
    }
}

// Execute the syntax checker
$verifier = new PHPSyntaxVerifier();
$verifier->run();