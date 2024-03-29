<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactList extends Model
{
    use HasFactory;

    public function contact_num()
    {
        return $this->hasMany(ContactNumber::class);
    }
}
