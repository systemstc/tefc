<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\CmsSeeder;
use Tests\TestCase;

class KpiDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_kpi_dashboard_page_returns_successful_response(): void
    {
        $response = $this->get('/kpi-dashboard');

        $response->assertStatus(200);
        $response->assertSee('TEFC Cluster Performance & KPI Dashboard');
        $response->assertSee('Spider Chart (Radar)');
        
        // Assert all 6 clusters are rendered
        $clusters = ['Ichalkaranji', 'Jaipur', 'Karur', 'Ludhiana', 'Surat', 'Varanasi'];
        foreach ($clusters as $cluster) {
            $response->assertSee($cluster);
        }

        // Assert key KPI indicators from the reference table are present
        $response->assertSee('Exporter Base');
        $response->assertSee('Number of unique exporters receiving advisory support');
        $response->assertSee('Training Programmes');
        $response->assertSee('Queries Handled');
        $response->assertSee('Cumulative');
    }

    public function test_dashboard_summary_redirects_to_kpi_dashboard(): void
    {
        $response = $this->get('/dashboard-summary');
        $response->assertRedirect('/kpi-dashboard');
    }

    public function test_main_navigation_includes_dashboard_link(): void
    {
        $this->seed(CmsSeeder::class);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('/kpi-dashboard');
        $response->assertSee('Dashboard');
    }

    public function test_kpi_dashboard_has_fullscreen_no_print_and_all_kpi_tabs(): void
    {
        $response = $this->get('/kpi-dashboard');
        $response->assertStatus(200);

        // Print option must NOT be present
        $response->assertDontSee('window.print()');
        $response->assertDontSee('Print Report');

        // Full Screen button must be present
        $response->assertSee('Full Screen');
        $response->assertSee('toggleFullScreen()');

        // Check tabs for all 6 KPIs + Overview
        $response->assertSee('tab-overview');
        $response->assertSee('tab-manpower');
        $response->assertSee('tab-exporter-base');
        $response->assertSee('tab-training');
        $response->assertSee('tab-newsletters');
        $response->assertSee('tab-queries');
        $response->assertSee('tab-focus-areas');

        // Test filtering by period and cluster
        $filteredResponse = $this->get('/kpi-dashboard?period=quarterly&cluster=surat');
        $filteredResponse->assertStatus(200);
        $filteredResponse->assertSee('Surat');
    }
}
