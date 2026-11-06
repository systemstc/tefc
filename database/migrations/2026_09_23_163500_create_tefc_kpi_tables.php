<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. KPI 1: Manpower & Advisory Personnel
        Schema::create('tefc_staff', function (Blueprint $table) {
            $table->id();
            $table->string('centre_code')->index(); // 'ichalkaranji', 'jaipur', 'karur', 'ludhiana', 'surat', 'varanasi'
            $table->string('name');
            $table->integer('age')->nullable();
            $table->string('designation'); // 'TEFC Officer', 'Textile Facilitator', 'Export Technical Advisor', etc.
            $table->string('education')->nullable(); // 'B.Tech Textile', 'MBA Int Business', etc.
            $table->integer('experience_years')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('status')->default('active'); // 'active', 'inactive'
            $table->date('joining_date')->nullable();
            $table->timestamps();
        });

        // 2. KPI 2: Advisory Sessions / Exporter Base Support
        Schema::create('tefc_advisories', function (Blueprint $table) {
            $table->id();
            $table->string('centre_code')->index();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('company_name');
            $table->string('iec_status')->default('regular'); // 'no_iec', 'iec_no_export', 'inactive_iec', 'new_iec', 'regular'
            $table->date('session_date');
            $table->string('advisory_topic'); // 'DGFT Registration', 'HSN & FTAs', 'Market Linkage', 'Export Finance'
            $table->text('outcome_notes')->nullable();
            $table->timestamps();
        });

        // 3. KPI 3: Training Programmes & Capacity Building
        Schema::create('tefc_trainings', function (Blueprint $table) {
            $table->id();
            $table->string('centre_code')->index();
            $table->string('title');
            $table->string('type')->default('training_programme'); // 'training_programme', 'buyer_seller_meet'
            $table->date('event_date');
            $table->string('venue')->nullable();
            $table->integer('participants_count')->default(0); // Exporters trained
            $table->text('topics_covered')->nullable();
            $table->timestamps();
        });

        // 4. KPI 4: Newsletters, Advisories & Trade Reports
        Schema::create('tefc_publications', function (Blueprint $table) {
            $table->id();
            $table->string('centre_code')->index();
            $table->string('title');
            $table->string('type')->default('newsletter'); // 'newsletter', 'trade_advisory', 'market_report'
            $table->date('issue_date');
            $table->text('summary')->nullable();
            $table->string('file_path')->nullable();
            $table->timestamps();
        });

        // 5. KPI 5: Queries Handled & SLA Resolution
        Schema::create('tefc_queries', function (Blueprint $table) {
            $table->id();
            $table->string('centre_code')->index();
            $table->string('company_name');
            $table->string('contact_person')->nullable();
            $table->string('subject');
            $table->string('category')->default('compliance'); // 'hsn_classification', 'compliance', 'documentation', 'incentives', 'other'
            $table->date('received_date');
            $table->date('resolved_date')->nullable();
            $table->string('status')->default('resolved'); // 'pending', 'resolved'
            $table->boolean('is_within_sla')->default(true);
            $table->text('resolution_notes')->nullable();
            $table->timestamps();
        });

        // 6. KPI 6: Cluster Profile & Target Coverage
        Schema::create('tefc_cluster_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('centre_code')->unique();
            $table->string('cluster_name');
            $table->string('state');
            $table->string('covered_districts');
            $table->integer('total_target_msmes')->default(500);
            $table->string('specialization')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tefc_cluster_profiles');
        Schema::dropIfExists('tefc_queries');
        Schema::dropIfExists('tefc_publications');
        Schema::dropIfExists('tefc_trainings');
        Schema::dropIfExists('tefc_advisories');
        Schema::dropIfExists('tefc_staff');
    }
};
