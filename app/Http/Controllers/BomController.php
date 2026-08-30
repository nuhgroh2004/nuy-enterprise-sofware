<?php

namespace App\Http\Controllers;

use App\Models\BomHeader;
use Illuminate\Http\Request;

class BomController extends Controller
{
    /* ============================================================
     | PAGE — BOM List
     ============================================================ */

    public function index()
    {
        $totalBoms = BomHeader::count();
        $activeCount = BomHeader::where('is_active', true)->count();
        $draftCount = BomHeader::whereHas('versions', function ($q) {
            $q->where('approval_state', 'draft');
        })->count();
        $approvedCount = BomHeader::whereHas('versions', function ($q) {
            $q->where('approval_state', 'approved')->where('is_default', true);
        })->count();

        return view('MRP&Production.page.bill-of-materials', compact(
            'totalBoms', 'activeCount', 'draftCount', 'approvedCount'
        ));
    }

    /* ============================================================
     | PAGE — Create BOM
     ============================================================ */

    public function create()
    {
        return view('MRP&Production.page.bom-create');
    }

    /* ============================================================
     | PAGE — BOM Detail
     ============================================================ */

    public function show(BomHeader $bom)
    {
        $bom->load([
            'company', 'plant', 'product.uom', 'productionProcess',
            'versions.components.product.uom',
            'versions.components.substitutes.product',
            'versions.routingVersion',
            'versions.outputUom',
            'versions.submittedBy',
            'versions.approvedByUser',
            'activeVersion',
        ]);

        return view('MRP&Production.page.bom-detail', compact('bom'));
    }

    /* ============================================================
     | PAGE — Edit BOM
     ============================================================ */

    public function edit(BomHeader $bom)
    {
        $bom->load([
            'company', 'plant', 'product.uom', 'productionProcess',
            'versions' => function ($q) {
                $q->latest('id');
            },
        ]);

        return view('MRP&Production.page.bom-edit', compact('bom'));
    }
}
