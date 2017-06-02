<?php

namespace App\Models;

use NFWP\Models\Model;
use NFWP\Models\Traits\HasAttachment;

class Post extends Model
{
    use HasAttachment;
    protected $table         = NFWP_DB_TABLE_PREFIX . 'posts';
    protected $primaryKey    = 'ID';
    public $defaultThumbnail = 'http://wpnfproject.dev/wp-content/uploads/2016/09/my-neighbor-totoro.jpg';
}
