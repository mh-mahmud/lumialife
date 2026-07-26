<?php

namespace App\Models;

use App\Support\MediaStorage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutletPageSetting extends Model
{
    use HasFactory;

    protected $fillable = ['banner_image'];

    public function bannerUrl(): string
    {
        return $this->banner_image
            ? MediaStorage::url($this->banner_image, 'outlets')
            : asset('feb/image-gallery/outletbanner.jpg');
    }
}
