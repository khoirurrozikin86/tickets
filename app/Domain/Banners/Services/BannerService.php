<?php

namespace App\Domain\Banners\Services;

use App\Domain\Banners\Actions\CreateBannerAction;
use App\Domain\Banners\Actions\DeleteBannerAction;
use App\Domain\Banners\Actions\UpdateBannerAction;
use App\Domain\Banners\DTOs\BannerData;
use App\Models\Banner;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class BannerService
{
    public function __construct(
        private readonly CreateBannerAction $createBannerAction,
        private readonly UpdateBannerAction $updateBannerAction,
        private readonly DeleteBannerAction $deleteBannerAction,
    ) {}

    public function create(array $data, ?UploadedFile $image = null): Banner
    {
        if ($image) {
            $data['image'] = 'storage/' . $image->store('banners', 'public');
        }

        return $this->createBannerAction->execute(
            BannerData::fromArray($data)
        );
    }

    public function update(
        Banner $banner,
        array $data,
        ?UploadedFile $image = null
    ): Banner {

        if ($image) {

            if ($banner->image) {
                $oldPath = str_replace('storage/', '', $banner->image);

                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $data['image'] = 'storage/' . $image->store('banners', 'public');
        } else {
            $data['image'] = $banner->image;
        }

        return $this->updateBannerAction->execute(
            $banner,
            BannerData::fromArray([
                ...$data,
                'id' => $banner->id,
            ])
        );
    }

    public function delete(Banner $banner): void
    {
        $this->deleteBannerAction->execute($banner);
    }
}
