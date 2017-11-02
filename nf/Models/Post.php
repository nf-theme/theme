<?php

namespace NF\Models;

use NF\Models\Model;
use NF\Models\Traits\HasAttachment;

class Post extends Model
{
    use HasAttachment;

    const POST_TYPE_ORIGIN = 'post';
    const POST_TYPE_PAGE   = 'page';

    const POST_STATUS_PUBLISH = 'publish';
    const POST_STATUS_DRAFT   = 'auto-draft';

    protected $table         = NFWP_DB_TABLE_PREFIX . 'posts';
    protected $primaryKey    = 'ID';
    public $defaultThumbnail = 'http://wpnfproject.dev/wp-content/uploads/2016/09/my-neighbor-totoro.jpg';

    public function scopeOrigin($query)
    {
        return $query->where('post_type', self::POST_TYPE_ORIGIN)
            ->where('post_status', self::POST_STATUS_PUBLISH);
    }

    public function scopePage($query)
    {
        return $query->where('post_type', self::POST_TYPE_PAGE)
            ->where('post_status', self::POST_STATUS_PUBLISH);
    }

}
