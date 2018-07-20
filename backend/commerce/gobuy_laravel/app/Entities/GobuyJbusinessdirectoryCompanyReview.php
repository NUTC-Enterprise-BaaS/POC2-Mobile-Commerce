<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class GobuyJbusinessdirectoryCompanyReview extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'subject', 'description', 'userId',
    					   'likeCount', 'dislikeCount', 'state',
    					   'companyId', 'creationDate', 'aproved',
    					   'abuseReported', 'rating'
    ];
}