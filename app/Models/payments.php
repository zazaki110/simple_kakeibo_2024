<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class payments extends Model
{
    use HasFactory;
    protected $table = 'payments';
    

    protected $fillable = ['id','user_id', 'spending','income','payment_name','date','created_at','created_by','updated_at','updated_by'];
}