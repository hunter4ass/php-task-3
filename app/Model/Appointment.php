<?php

namespace Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
   use HasFactory;

   public $timestamps = false;
   protected $fillable = [
       'patient_id',
       'doctor_id',
       'service_type',
       'appointment_date',
       'appointment_time',
       'status',
       'complaint',
       'notes',
       'room',
       'attachment_path'
   ];

   // Связь с пациентом
   public function patient()
   {
       return $this->belongsTo(User::class, 'patient_id');
   }

   // Связь с врачом
   public function doctor()
   {
       return $this->belongsTo(User::class, 'doctor_id');
   }
}

