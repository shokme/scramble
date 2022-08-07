<?php

namespace Dedoc\Documentor\Support\Generator;

class OpenApi
{
    private string $version;

    private InfoObject $info;

    /** @var Path[] */
    private array $paths = [];

    public function __construct(string $version)
    {
        $this->version = $version;
    }

    public static function make(string $version)
    {
        return new self($version);
    }

    public function addInfo(InfoObject $info)
    {
        $this->info = $info;

        return $this;
    }

    public function addPath(Path $buildPath)
    {
        $this->paths[] = $buildPath;

        return $this;
    }

    public function toArray()
    {
        $result = [
            'openapi' => $this->version,
            'info' => $this->info->toArray(),
        ];

        if (count($this->paths)) {
            $paths = [];

            foreach ($this->paths as $pathBuilder) {
                $paths['/'.$pathBuilder->path] = array_merge(
                    $paths['/'.$pathBuilder->path] ?? [],
                    $pathBuilder->toArray(),
                );
            }

            $result['paths'] = $paths;
        }

        return $result;
    }
}