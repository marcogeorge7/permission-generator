<?php

namespace PermissionGenerator\Generator;

use PermissionGenerator\Generator\Exception\InvalidDirectoriesConfiguration;
use PermissionGenerator\Generator\Exception\InvalidExtensionsConfiguration;

class PermissionScanner
{
    /**
     * @param string[] $extensions
     * @param string[] $directories
     * @return Permission[]
     * @throws InvalidDirectoriesConfiguration
     * @throws InvalidExtensionsConfiguration
     */
    public function scan(array $extensions, array $directories): array
    {
        if (empty($extensions)) {
            throw new InvalidExtensionsConfiguration();
        }

        if (empty($directories)) {
            throw new InvalidDirectoriesConfiguration();
        }

        $ext = implode(',', $extensions);

        return array_reduce($directories, function (array $collection, string $directory) use ($ext): array {
            return array_merge(
                $collection,
                $this->scanDirectory($directory, $ext)
            );
        }, []);
    }

    /**
     * @return Permission[]
     */
    private function scanDirectory(string $path, string $extensions): array
    {
        $files = glob_recursive("{$path}/*.{{$extensions}}", GLOB_BRACE);

        return array_reduce($files, function (array $keys, $file): array {
            $content = $this->getFileContent($file);

            return array_merge(
                $keys,
                $this->getKeysFromFunction('@can', $content),
                $this->getKeysFromFunction('Gate::allows', $content)
            );
        }, []);
    }

    private function getFileContent(string $filePath): string
    {
        $content = (string) file_get_contents($filePath) ?? '';

        return str_replace("\n", ' ', $content);
    }

    /**
     * @return string[]
     */
    private function getKeysFromFunction(string $functionName, string $content): array
    {
        preg_match_all("#{$functionName} *\( *((['\"])((?:\\\\\\2|.)*?)\\2)#", $content, $matches);

        $matches = $matches[1] ?? [];

        return array_reduce($matches, function (array $keys, string $match) {
            $quote = $match[0];
            $match = trim($match, $quote);
            $key = ($quote === '"') ? stripcslashes($match) : str_replace(["\\'", "\\\\"], ["'", "\\"], $match);

            return $key ?
                array_merge($keys, [$key => new Permission($key, '')]) :
                $keys;
        }, []);
    }
}
