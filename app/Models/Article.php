<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable
     * $fillable: array of attributes that are mass assignable
     * $guarded: array of attributes that are not mass assignable
     * REMARK: the save() methode does not pass the guarded attributes!
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Relationship between models
     * hasMany('model', 'foreign_key', 'primary_key'):  method name is lowercase and plural case
     * belongsTo('model', 'foreign_key', 'primary_key')->withDefaults():  method name is lowercase and singular case
     */
    public function garment(){
        return $this->belongsTo(Garment::class)->withDefault();  // a Article belongs to a Garment
    }
    public function size(){
        return $this->belongsTo(Size::class)->withDefault();  // a Article belongs to a Size
    }
    public function articleorders()
    {
        return $this->hasMany(ArticleOrder::class);
    }

    /**
     * Accessors and mutators (method name is the attribute name)
     * get: transform the attribute after it has retrieved from database
     * set: transform the attribute before it is sent to database
     */


    /**
     * Add additional attributes that do not have a corresponding column in your database
     * REMARK: additional attributes are not automatically included to the model!
     *    - add the attributes to the $appends array to include them always to the model
     *    - or append the attributes in runtime with Model::get()->append([])
     */
    protected function sizeName(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => Size::find($attributes['size_id'])->name,
        );
    }

    protected function garmentName(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => Garment::find($attributes['garment_id'])->name,
        );
    }

    protected $appends = ['size_name', 'garment_name'];


    /**
     * Apply the scope to a given Eloquent query builder
     * prefix the method name with 'scope' e.g. 'scopeIsActive()'
     * Utilize the scope in the controller  Model::is_active()->get();
     */


    /**
     * Add attributes that should be hidden to the $hidden array
     */
    protected $hidden = [];
}
