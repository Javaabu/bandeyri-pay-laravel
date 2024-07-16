<?php
$packageSetup = new PackageSetup();

$defaultAuthorName = $packageSetup->getDefaultAuthorName();
$defaultAuthorEmail = $packageSetup->getDefaultAuthorEmail();

$package_name = '';

while (empty($package_name)) {
    echo "Enter the package name (in snake case): ";
    $package_name = trim(fgets(STDIN));
}

echo "Enter the package description: ";
$package_description = trim(fgets(STDIN));

echo "Package author name [default: {$defaultAuthorName}]: ";
$author_name = trim(fgets(STDIN));

echo "Package author email [default: {$defaultAuthorEmail}]: ";
$author_email = trim(fgets(STDIN));

echo "Package author github username: ";
$github_username = trim(fgets(STDIN));

$packageSetup->setPackage($package_name, $package_description, $author_name, $author_email, $github_username);
$packageSetup->replacePlaceholders();
$packageSetup->renameFiles();
$packageSetup->removeReadmeInstructions();

echo "Package setup completed successfully\n";

$sec = 5;
while ($sec > 0) {
    echo "This file will be auto destructed in {$sec} seconds\n";
    sleep(1);
    $sec--;
}

unlink(__FILE__);


class PackageSetup
{
    private string $packageName;
    private string $packageKeyWords;
    private string $packageTitle;
    private string $packageClassName;
    private string $packageDescription;
    private string $packageAuthorName;
    private string $packageAuthorEmail;
    private string $githubUsername;

    public function setPackage(
        string $package_name,
        string $package_description,
        string $author_name = null,
        string $author_email = null,
        string $github_username = null,
    ): void
    {
        $this->setPackageName($package_name);
        $this->setPackageKeyWord($package_name);
        $this->setPackageTitle($package_name);
        $this->setPackageClassName($package_name);
        $this->setPackageDescription($package_description);
        $this->setPackageAuthorName($author_name);
        $this->setPackageAuthorEmail($author_email);
        $this->setGithubUsername($github_username);
    }

    public function replacePlaceholders(): void
    {
        $this->replacePackageName();
        $this->replacePackageKeyWords();
        $this->replacePackageTitle();
        $this->replacePackageClassName();
        $this->replacePackageDescription();
        $this->replacePackageAuthorName();
        $this->replacePackageAuthorEmail();
        $this->replaceGithubUsername();
    }

    public function renameFiles(): void
    {
        $this->renameConfigFile();
        $this->renameServiceProviderFile();
    }

    public function setPackageName(string $name): void
    {
        $this->packageName = $this->stringToSlug($name);

    }

    public function setPackageKeyWord(string $name): void
    {
        $this->packageKeyWords = $this->stringToSlug($name, '_');
    }

    public function setPackageTitle(string $title): void
    {
        $this->packageTitle = $this->stringToTitle($title);
    }

    public function setPackageClassName(string $name): void
    {
        $this->packageClassName = $this->stringToClassName($name);
    }

    public function setPackageDescription(string $description): void
    {
        $this->packageDescription = $description;
    }

    public function setPackageAuthorName(string $author_name = null): void
    {
        $this->packageAuthorName = $author_name;
    }

    public function setPackageAuthorEmail(string $author_email = null): void
    {
        $this->packageAuthorEmail = $author_email;
    }

    public function setGithubUsername(string $github_username = null): void
    {
        $this->githubUsername = $github_username;
    }

    public function getPackageName(): string
    {
        return $this->packageName;
    }

    public function getPackageKeyWords(): string
    {
        return $this->packageKeyWords;
    }

    public function getPackageTitle(): string
    {
        return $this->packageTitle;
    }

    public function getPackageClassName(): string
    {
        return $this->packageClassName;
    }

    public function getPackageDescription(): string
    {
        return $this->packageDescription;
    }

    public function getPackageAuthorName(): string
    {
        if (empty($this->packageAuthorName)) {
            return $this->getDefaultAuthorName();
        }

        return $this->packageAuthorName;
    }

    public function getPackageAuthorEmail(): string
    {
        if (empty($this->packageAuthorEmail)) {
            return $this->getDefaultAuthorEmail();
        }

        return $this->packageAuthorEmail;
    }

    public function getGithubUsername(): string
    {
        return $this->githubUsername;
    }

    public function replacePackageName(): void
    {
        foreach ($this->getFiles() as $file) {
            $this->replacePlaceholder($file, '{your-package}', $this->getPackageName());
        }
    }

    public function replacePackageKeyWords(): void
    {
        foreach ($this->getFiles() as $file) {
            $this->replacePlaceholder($file, '{your_package}', $this->getPackageKeyWords());
        }
    }

    public function replacePackageTitle(): void
    {
        foreach ($this->getFiles() as $file) {
            $this->replacePlaceholder($file, '{Your Package}', $this->getPackageTitle());
        }
    }

    public function replacePackageClassName(): void
    {
        foreach ($this->getFiles() as $file) {
            $this->replacePlaceholder($file, '{YourPackage}', $this->getPackageClassName());
        }
    }

    public function replacePackageDescription(): void
    {
        foreach ($this->getFiles() as $file) {
            $this->replacePlaceholder($file, '{package description}', $this->getPackageDescription());
        }
    }

    public function replacePackageAuthorName(): void
    {
        foreach ($this->getFiles() as $file) {
            $this->replacePlaceholder($file, '{author_name}', $this->getPackageAuthorName());
        }
    }

    public function replacePackageAuthorEmail(): void
    {
        foreach ($this->getFiles() as $file) {
            $this->replacePlaceholder($file, '{author_email}', $this->getPackageAuthorEmail());
        }
    }

    public function replaceGithubUsername(): void
    {
        $authorName = $this->getPackageAuthorName();
        $githubUsername = $this->getGithubUsername();
        $replacedContent = "[{$authorName} (@{$githubUsername})](https://github.com/{$githubUsername})";
        foreach ($this->getFiles() as $file) {
            $this->replacePlaceholder($file, '{Contributor}', $replacedContent);
        }
    }

    public function replacePlaceholder($file, $placeholder, $value): void
    {
        $content = file_get_contents($file);
        $content = str_replace($placeholder, $value, $content);
        file_put_contents($file, $content);
    }

    public function getDefaultAuthorName(): string
    {
        return $this->stringToTitle(strtolower(trim(shell_exec('git config user.name'))));
    }

    public function getDefaultAuthorEmail(): string
    {
        return strtolower(trim(shell_exec('git config user.email')));
    }

    public function getFiles(): array
    {
        $skipDirectories = array('vendor', '.git', '.idea', 'node_modules');
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__));
        $files = array();

        foreach ($iterator as $file) {
            // Skip if it's not a file
            if (!$file->isFile()) {
                continue;
            }

            // Skip if it's in the directory we want to skip
            $skip = false;
            foreach ($skipDirectories as $skipDirectory) {
                if (str_contains($file->getPathname(), $skipDirectory)) {
                    $skip = true;
                    break;
                }
            }

            if ($skip) {
                continue;
            }

            if ($file->getFilename() === "init.php") {
                continue;
            }

            // Get the file path
            $filePath = $file->getPathname();

            // Add file path to the array
            $files[] = $filePath;
        }

        return $files;
    }

    public function stringToSlug($string, string $separator = '-'): string
    {
        $slug = str_replace(' ', "{$separator}", $string);
        $slug = str_replace('_', "{$separator}", $slug);
        return strtolower($slug);
    }

    function stringToTitle($string): string
    {
        $string = str_replace(array('_', '-'), ' ', $string);
        return ucwords($string);
    }

    function stringToClassName($string): string
    {
        $string = str_replace(array('_', '-'), ' ', $string);
        $title =  ucwords($string);
        return str_replace(' ', '', $title);
    }

    public function renameConfigFile(): void
    {
        $config_file = __DIR__ . '/config/{your-package}.php';
        $new_config_file = __DIR__ . "/config/{$this->getPackageName()}.php";
        rename($config_file, $new_config_file);
    }

    public function renameServiceProviderFile(): void
    {
        $service_provider_file = __DIR__ . '/src/{YourPackage}ServiceProvider.php';
        $new_service_provider_file = __DIR__ . "/src/{$this->getPackageClassName()}ServiceProvider.php";
        rename($service_provider_file, $new_service_provider_file);
    }

    public function removeReadmeInstructions(): void
    {
        $readme_file = __DIR__ . '/README.md';
        $content = file_get_contents($readme_file);
        // Remove from --- to ---
        $content = preg_replace('/---(.*)---/s', '', $content);
        file_put_contents($readme_file, $content);
    }
}
