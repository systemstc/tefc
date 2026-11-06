<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExporterProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'company_name', 'iec_code', 'gst_no', 'profile_description',
        'hs_codes', 'production_capacity', 'certifications',
        'contact_phone', 'website', 'certificate_path',
        'factory_address', 'district', 'state', 'year_of_establishment',
        'textile_segment', 'main_product_hsn', 'installed_capacity',
        'major_machineries', 'capacity_unit', 'monthly_production',
        'annual_turnover', 'is_exporter', 'export_turnover',
        'major_exporting_countries', 'product_images', 'export_potential',
        'remarks', 'tefc_centre', 'rejection_reason',
        'gst_legal_name', 'gst_trade_name', 'gst_constitution', 'gst_status',
        'gst_signatories', 'gst_ownership_verified', 'gst_matched_signatory'
    ];

    protected $casts = [
        'product_images' => 'array',
        'gst_signatories' => 'array',
        'gst_ownership_verified' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
