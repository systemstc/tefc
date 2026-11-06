<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Setting;
use App\Models\Menu;

class CmsController extends Controller
{
    public function show($slug = 'home')
    {
        // Only load active sections for public views
        $page = Page::with(['sections' => function ($query) {
            $query->where('is_active', true)->orderBy('order');
        }])->where('slug', $slug)->where('is_active', true)->firstOrFail();

        if ($slug === 'clusters') {
            $registeredExporters = \App\Models\User::whereHas('roles', fn($q) => $q->whereIn('slug', ['seller', 'exporter']))
                ->where('status', 'active')
                ->with('exporterProfile')
                ->get()
                ->groupBy(function($u) {
                    $centre = $u->tefc_centre ?: ($u->exporterProfile->tefc_centre ?? '');
                    return strtolower(trim($centre));
                });

            return view('clusters', compact('page', 'registeredExporters'));
        }

        if ($slug === 'news') {
            return view('news', compact('page'));
        }

        if ($slug === 'about') {
            return view('about', compact('page'));
        }

        if ($slug === 'media') {
            return view('media', compact('page'));
        }

        if ($slug === 'help-support') {
            return view('help_support', compact('page'));
        }

        if ($slug === 'fta-benefits') {
            return view('fta-benefits', compact('page'));
        }

        if ($slug === 'online-services') {
            return view('online-services', compact('page'));
        }

        if ($slug === 'coo') {
            return view('coo', compact('page'));
        }

        if ($slug === 'ecgc') {
            return view('ecgc', compact('page'));
        }

        if ($slug === 'letter-of-credit') {
            return view('letter-of-credit', compact('page'));
        }

        if ($slug === 'hs-code') {
            return view('hs-code', compact('page'));
        }

        if ($slug === 'iec') {
            return view('iec', compact('page'));
        }

        if ($slug === 'kpi-dashboard' || $slug === 'dashboard') {
            return redirect()->route('kpi.dashboard');
        }

        // The layout handles the loop through sections
        return view('home', compact('page'));
    }

    public function sitemap()
    {
        return view('sitemap');
    }
}
