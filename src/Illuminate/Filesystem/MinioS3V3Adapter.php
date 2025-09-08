<?php

namespace Illuminate\Filesystem;

use Aws\S3\S3Client;
use League\Flysystem\AwsS3V3\AwsS3V3Adapter as S3Adapter;
use League\Flysystem\FilesystemOperator;

class MinioS3V3Adapter extends AwsS3V3Adapter
{
    protected ?AwsS3V3Adapter $virtualAdapter = null;

    public static function fromAwsS3V3AdapterWithVirtualAdapter(AwsS3V3Adapter $parent, AwsS3V3Adapter $virtualAdapter): self
    {
        return (new self(
            $parent->getDriver(),
            $parent->getAdapter(),
            $parent->getConfig(),
            $parent->getClient()
        ))->withVirtualAdapter($virtualAdapter);
    }

    /**
     * {@inheritdoc}
     */
    public function temporaryUrl($path, $expiration, array $options = [])
    {
        if ($this->virtualAdapter) {
            return $this->virtualAdapter->temporaryUrl(
                $path,
                $expiration,
                $options
            );
        }

        return parent::temporaryUrl(
            $path,
            $expiration,
            $options
        );
    }

    /**
     * {@inheritdoc}
     */
    public function temporaryUploadUrl($path, $expiration, array $options = [])
    {
        if ($this->virtualAdapter) {
            return $this->virtualAdapter->temporaryUploadUrl(
                $path,
                $expiration,
                $options
            );
        }

        return parent::temporaryUploadUrl(
            $path,
            $expiration,
            $options
        );
    }

    protected function withVirtualAdapter(AwsS3V3Adapter $virtualAdapter): self
    {
        $this->virtualAdapter = $virtualAdapter;

        return $this;
    }
}