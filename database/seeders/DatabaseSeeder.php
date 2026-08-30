<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\BomComponent;
use App\Models\BomHeader;
use App\Models\BomSubstitute;
use App\Models\BomVersion;
use App\Models\CalendarException;
use App\Models\Company;
use App\Models\CostCenter;
use App\Models\CostVariance;
use App\Models\CustomFieldDefinition;
use App\Models\Demand;
use App\Models\DemandLine;
use App\Models\DemandLine as DemandLineModel;
use App\Models\DowntimeRecord;
use App\Models\Employee;
use App\Models\InspectionResult;
use App\Models\MasterProductionSchedule;
use App\Models\MasterProductionSchedule as MpsModel;
use App\Models\Machine;
use App\Models\MaintenanceHistory;
use App\Models\MaintenanceOrder;
use App\Models\MaintenanceSchedule;
use App\Models\MaterialConsumption;
use App\Models\MaterialRequirement;
use App\Models\NonConformance;
use App\Models\NumberingSequence;
use App\Models\Plant;
use App\Models\PlannedOrder;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductCost;
use App\Models\ProductType;
use App\Models\ProductUom;
use App\Models\ProductionCalendar;
use App\Models\ProductionCostTransaction;
use App\Models\ProductionOrder;
use App\Models\ProductionProcess;
use App\Models\ProductionResult;
use App\Models\QualityInspection;
use App\Models\ReasonCode;
use App\Models\ReworkOrder;
use App\Models\RoutingHeader;
use App\Models\RoutingOperation;
use App\Models\RoutingOperationDependency;
use App\Models\RoutingVersion;
use App\Models\Scrap;
use App\Models\Shift;
use App\Models\StatusDefinition;
use App\Models\StockBalance;
use App\Models\StockMovement;
use App\Models\UnitOfMeasure;
use App\Models\Warehouse;
use App\Models\WarehouseLocation;
use App\Models\WorkCenter;
use App\Models\WorkOrder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedCoreMasterData();
        $this->seedProductsAndBom();
        $this->seedProductUoms();
        $this->seedRouting();
        $this->seedPlanning();
        $this->seedProduction();
        $this->seedInventory();
        $this->seedQuality();
        $this->seedMaintenance();
        $this->seedCosting();
        $this->seedSettings();
    }

    private function seedCoreMasterData(): void
    {
        // Company
        $company = Company::updateOrCreate(
            ['code' => 'DMFG'],
            [
                'name' => 'Demo Manufacturing Co.',
                'legal_name' => 'Demo Manufacturing Company Ltd.',
                'address' => '123 Industrial Boulevard',
                'city' => 'Jakarta',
                'state' => 'DKI Jakarta',
                'country' => 'Indonesia',
                'postal_code' => '12345',
                'phone' => '+62-21-555-0100',
                'email' => 'info@demomanufacturing.co.id',
                'tax_id' => '01.234.567.8-901.000',
                'is_active' => true,
            ]
        );

        // Plant
        $plantMain = Plant::updateOrCreate(
            ['company_id' => $company->id, 'code' => 'PLT-MAIN'],
            [
                'name' => 'Main Factory',
                'address' => '123 Industrial Boulevard, Building A',
                'city' => 'Jakarta',
                'phone' => '+62-21-555-0101',
                'is_active' => true,
            ]
        );

        $plantWarehouse = Plant::updateOrCreate(
            ['company_id' => $company->id, 'code' => 'PLT-WH'],
            [
                'name' => 'Warehouse Facility',
                'address' => '125 Industrial Boulevard, Building B',
                'city' => 'Jakarta',
                'phone' => '+62-21-555-0102',
                'is_active' => true,
            ]
        );

        // Warehouses
        $whRaw = Warehouse::updateOrCreate(
            ['company_id' => $company->id, 'code' => 'WH-RAW'],
            [
                'plant_id' => $plantMain->id,
                'name' => 'Raw Material Warehouse',
                'type' => 'raw_material',
                'address' => 'Building A, Ground Floor',
                'is_active' => true,
            ]
        );

        $whWip = Warehouse::updateOrCreate(
            ['company_id' => $company->id, 'code' => 'WH-WIP'],
            [
                'plant_id' => $plantMain->id,
                'name' => 'Work In Progress Area',
                'type' => 'work_in_progress',
                'address' => 'Building A, Production Floor',
                'is_active' => true,
            ]
        );

        $whFg = Warehouse::updateOrCreate(
            ['company_id' => $company->id, 'code' => 'WH-FG'],
            [
                'plant_id' => $plantMain->id,
                'name' => 'Finished Goods Warehouse',
                'type' => 'finished_goods',
                'address' => 'Building B, Section 1',
                'is_active' => true,
            ]
        );

        // Warehouse Locations
        $locRawA = WarehouseLocation::updateOrCreate(
            ['warehouse_id' => $whRaw->id, 'code' => 'A-01-01'],
            ['company_id' => $company->id, 'name' => 'Zone A, Rack 01, Shelf 01', 'zone' => 'A', 'aisle' => '1', 'rack' => '01', 'shelf' => '01', 'is_active' => true]
        );

        $locRawB = WarehouseLocation::updateOrCreate(
            ['warehouse_id' => $whRaw->id, 'code' => 'B-01-01'],
            ['company_id' => $company->id, 'name' => 'Zone B, Rack 01, Shelf 01', 'zone' => 'B', 'aisle' => '1', 'rack' => '01', 'shelf' => '01', 'is_active' => true]
        );

        $locFg1 = WarehouseLocation::updateOrCreate(
            ['warehouse_id' => $whFg->id, 'code' => 'FG-01-01'],
            ['company_id' => $company->id, 'name' => 'FG Zone, Rack 01, Shelf 01', 'zone' => 'FG', 'aisle' => '1', 'rack' => '01', 'shelf' => '01', 'is_active' => true]
        );

        // UOM
        $uomPcs = UnitOfMeasure::updateOrCreate(['code' => 'PCS'], ['name' => 'Piece', 'symbol' => 'pcs', 'category' => 'count', 'decimal_places' => 0, 'is_active' => true]);
        $uomKg = UnitOfMeasure::updateOrCreate(['code' => 'KG'], ['name' => 'Kilogram', 'symbol' => 'kg', 'category' => 'weight', 'decimal_places' => 2, 'is_active' => true]);
        $uomM = UnitOfMeasure::updateOrCreate(['code' => 'M'], ['name' => 'Meter', 'symbol' => 'm', 'category' => 'length', 'decimal_places' => 2, 'is_active' => true]);
        $uomM2 = UnitOfMeasure::updateOrCreate(['code' => 'M2'], ['name' => 'Square Meter', 'symbol' => 'm²', 'category' => 'area', 'decimal_places' => 2, 'is_active' => true]);
        $uomLiter = UnitOfMeasure::updateOrCreate(['code' => 'L'], ['name' => 'Liter', 'symbol' => 'L', 'category' => 'volume', 'decimal_places' => 2, 'is_active' => true]);
        $uomPair = UnitOfMeasure::updateOrCreate(['code' => 'PAIR'], ['name' => 'Pair', 'symbol' => 'pair', 'category' => 'count', 'decimal_places' => 0, 'is_active' => true]);
        $uomBox = UnitOfMeasure::updateOrCreate(['code' => 'BOX'], ['name' => 'Box', 'symbol' => 'box', 'category' => 'count', 'decimal_places' => 0, 'is_active' => true]);
        $uomRoll = UnitOfMeasure::updateOrCreate(['code' => 'ROLL'], ['name' => 'Roll', 'symbol' => 'roll', 'category' => 'count', 'decimal_places' => 0, 'is_active' => true]);
        $uomHour = UnitOfMeasure::updateOrCreate(['code' => 'HR'], ['name' => 'Hour', 'symbol' => 'hr', 'category' => 'time', 'decimal_places' => 2, 'is_active' => true]);

        // UOM Conversions
        DB::table('uom_conversions')->updateOrInsert(
            ['from_uom_id' => $uomKg->id, 'to_uom_id' => $uomKg->id],
            ['conversion_factor' => 1, 'is_base' => true, 'created_at' => now(), 'updated_at' => now()]
        );

        // Product Categories
        $catMaterial = ProductCategory::updateOrCreate(['company_id' => $company->id, 'code' => 'MAT'], ['name' => 'Raw Materials', 'is_active' => true]);
        $catComponent = ProductCategory::updateOrCreate(['company_id' => $company->id, 'code' => 'CMP'], ['name' => 'Components', 'is_active' => true]);
        $catSemi = ProductCategory::updateOrCreate(['company_id' => $company->id, 'code' => 'SEMI'], ['name' => 'Semi-Finished Goods', 'is_active' => true]);
        $catFinished = ProductCategory::updateOrCreate(['company_id' => $company->id, 'code' => 'FG'], ['name' => 'Finished Goods', 'is_active' => true]);
        $catConsumable = ProductCategory::updateOrCreate(['company_id' => $company->id, 'code' => 'CON'], ['name' => 'Consumables', 'is_active' => true]);
        $catPackaging = ProductCategory::updateOrCreate(['company_id' => $company->id, 'code' => 'PKG'], ['name' => 'Packaging', 'is_active' => true]);

        // Product Types
        $typeRaw = ProductType::updateOrCreate(['code' => 'RAW'], ['name' => 'Raw Material', 'is_active' => true]);
        $typeComponent = ProductType::updateOrCreate(['code' => 'COMP'], ['name' => 'Component', 'is_active' => true]);
        $typeSemi = ProductType::updateOrCreate(['code' => 'SEMI'], ['name' => 'Semi-Finished', 'is_active' => true]);
        $typeFinished = ProductType::updateOrCreate(['code' => 'FG'], ['name' => 'Finished Good', 'is_active' => true]);
        $typeConsumable = ProductType::updateOrCreate(['code' => 'CONS'], ['name' => 'Consumable', 'is_active' => true]);
        $typePackaging = ProductType::updateOrCreate(['code' => 'PACK'], ['name' => 'Packaging Material', 'is_active' => true]);

        // Production Processes
        $processCutting = ProductionProcess::updateOrCreate(['company_id' => $company->id, 'code' => 'PROC-CUT'], ['name' => 'Cutting', 'description' => 'Material cutting process', 'is_active' => true]);
        $processAssembly = ProductionProcess::updateOrCreate(['company_id' => $company->id, 'code' => 'PROC-ASM'], ['name' => 'Assembly', 'description' => 'Product assembly process', 'is_active' => true]);
        $processFinishing = ProductionProcess::updateOrCreate(['company_id' => $company->id, 'code' => 'PROC-FIN'], ['name' => 'Finishing', 'description' => 'Surface finishing and quality treatment', 'is_active' => true]);
        $processPackaging = ProductionProcess::updateOrCreate(['company_id' => $company->id, 'code' => 'PROC-PKG'], ['name' => 'Packaging', 'description' => 'Final packaging process', 'is_active' => true]);

        // Work Centers
        $wcCutting = WorkCenter::updateOrCreate(
            ['company_id' => $company->id, 'code' => 'WC-CUT'],
            [
                'plant_id' => $plantMain->id,
                'name' => 'Cutting Center',
                'production_process_id' => $processCutting->id,
                'capacity_per_hour' => 100,
                'uom_id' => $uomPcs->id,
                'setup_cost_per_hour' => 15.00,
                'run_cost_per_hour' => 25.00,
                'labor_cost_per_hour' => 20.00,
                'is_active' => true,
            ]
        );

        $wcAssembly = WorkCenter::updateOrCreate(
            ['company_id' => $company->id, 'code' => 'WC-ASM'],
            [
                'plant_id' => $plantMain->id,
                'name' => 'Assembly Center',
                'production_process_id' => $processAssembly->id,
                'capacity_per_hour' => 50,
                'uom_id' => $uomPcs->id,
                'setup_cost_per_hour' => 20.00,
                'run_cost_per_hour' => 35.00,
                'labor_cost_per_hour' => 25.00,
                'is_active' => true,
            ]
        );

        $wcFinishing = WorkCenter::updateOrCreate(
            ['company_id' => $company->id, 'code' => 'WC-FIN'],
            [
                'plant_id' => $plantMain->id,
                'name' => 'Finishing Center',
                'production_process_id' => $processFinishing->id,
                'capacity_per_hour' => 80,
                'uom_id' => $uomPcs->id,
                'setup_cost_per_hour' => 10.00,
                'run_cost_per_hour' => 20.00,
                'labor_cost_per_hour' => 18.00,
                'is_active' => true,
            ]
        );

        // Machines
        $machineCut1 = Machine::updateOrCreate(
            ['company_id' => $company->id, 'code' => 'MCH-CUT-01'],
            [
                'plant_id' => $plantMain->id,
                'work_center_id' => $wcCutting->id,
                'name' => 'CNC Cutter 01',
                'model' => 'CNC-3000',
                'serial_number' => 'SN-CNC-001',
                'capacity_per_hour' => 120,
                'purchase_date' => '2024-01-15',
                'status' => 'active',
                'is_active' => true,
            ]
        );

        $machineAsm1 = Machine::updateOrCreate(
            ['company_id' => $company->id, 'code' => 'MCH-ASM-01'],
            [
                'plant_id' => $plantMain->id,
                'work_center_id' => $wcAssembly->id,
                'name' => 'Assembly Station 01',
                'model' => 'ASM-PRO',
                'serial_number' => 'SN-ASM-001',
                'capacity_per_hour' => 60,
                'purchase_date' => '2024-02-20',
                'status' => 'active',
                'is_active' => true,
            ]
        );

        $machineFin1 = Machine::updateOrCreate(
            ['company_id' => $company->id, 'code' => 'MCH-FIN-01'],
            [
                'plant_id' => $plantMain->id,
                'work_center_id' => $wcFinishing->id,
                'name' => 'Polishing Machine 01',
                'model' => 'POL-500',
                'serial_number' => 'SN-POL-001',
                'capacity_per_hour' => 90,
                'purchase_date' => '2024-03-10',
                'status' => 'active',
                'is_active' => true,
            ]
        );

        // Shifts
        $shiftMorning = Shift::updateOrCreate(
            ['company_id' => $company->id, 'code' => 'SHIFT-AM'],
            ['name' => 'Morning Shift', 'start_time' => '06:00', 'end_time' => '14:00', 'break_minutes' => 30, 'is_active' => true]
        );

        $shiftAfternoon = Shift::updateOrCreate(
            ['company_id' => $company->id, 'code' => 'SHIFT-PM'],
            ['name' => 'Afternoon Shift', 'start_time' => '14:00', 'end_time' => '22:00', 'break_minutes' => 30, 'is_active' => true]
        );

        // Production Calendar
        $calendar = ProductionCalendar::updateOrCreate(
            ['company_id' => $company->id, 'code' => 'CAL-STD'],
            ['name' => 'Standard Production Calendar', 'description' => 'Monday-Friday working days', 'is_default' => true, 'is_active' => true]
        );

        CalendarException::updateOrCreate(
            ['production_calendar_id' => $calendar->id, 'exception_date' => '2026-01-01'],
            ['name' => 'New Year Day', 'type' => 'holiday', 'is_working_day' => false]
        );

        CalendarException::updateOrCreate(
            ['production_calendar_id' => $calendar->id, 'exception_date' => '2026-08-17'],
            ['name' => 'Independence Day', 'type' => 'holiday', 'is_working_day' => false]
        );

        // Employees
        $empOperator = Employee::updateOrCreate(
            ['company_id' => $company->id, 'code' => 'EMP-001'],
            [
                'plant_id' => $plantMain->id,
                'first_name' => 'Budi',
                'last_name' => 'Santoso',
                'email' => 'budi.santoso@demomanufacturing.co.id',
                'department' => 'Production',
                'position' => 'Machine Operator',
                'hire_date' => '2023-06-01',
                'is_active' => true,
            ]
        );

        $empInspector = Employee::updateOrCreate(
            ['company_id' => $company->id, 'code' => 'EMP-002'],
            [
                'plant_id' => $plantMain->id,
                'first_name' => 'Siti',
                'last_name' => 'Rahayu',
                'email' => 'siti.rahayu@demomanufacturing.co.id',
                'department' => 'Quality',
                'position' => 'Quality Inspector',
                'hire_date' => '2023-07-15',
                'is_active' => true,
            ]
        );

        $empSupervisor = Employee::updateOrCreate(
            ['company_id' => $company->id, 'code' => 'EMP-003'],
            [
                'plant_id' => $plantMain->id,
                'first_name' => 'Andi',
                'last_name' => 'Wijaya',
                'email' => 'andi.wijaya@demomanufacturing.co.id',
                'department' => 'Production',
                'position' => 'Production Supervisor',
                'hire_date' => '2022-01-10',
                'is_active' => true,
            ]
        );

        $empTechnician = Employee::updateOrCreate(
            ['company_id' => $company->id, 'code' => 'EMP-004'],
            [
                'plant_id' => $plantMain->id,
                'first_name' => 'Dedi',
                'last_name' => 'Kurniawan',
                'email' => 'dedi.kurniawan@demomanufacturing.co.id',
                'department' => 'Maintenance',
                'position' => 'Maintenance Technician',
                'hire_date' => '2023-03-01',
                'is_active' => true,
            ]
        );

        // Reason Codes
        $reasonScrapDefect = ReasonCode::updateOrCreate(['company_id' => $company->id, 'code' => 'RS-DEFECT'], ['name' => 'Material Defect', 'category' => 'scrap', 'description' => 'Material has quality defect', 'is_active' => true]);
        $reasonDowntimeBreak = ReasonCode::updateOrCreate(['company_id' => $company->id, 'code' => 'RD-BREAK'], ['name' => 'Machine Breakdown', 'category' => 'downtime', 'description' => 'Unexpected machine failure', 'is_active' => true]);
        $reasonRework = ReasonCode::updateOrCreate(['company_id' => $company->id, 'code' => 'RR-WRONG'], ['name' => 'Wrong Assembly', 'category' => 'rework', 'description' => 'Assembly done incorrectly', 'is_active' => true]);
        $reasonQcFail = ReasonCode::updateOrCreate(['company_id' => $company->id, 'code' => 'RQ-FAIL'], ['name' => 'QC Inspection Fail', 'category' => 'quality', 'description' => 'Failed quality control inspection', 'is_active' => true]);
        $reasonCancel = ReasonCode::updateOrCreate(['company_id' => $company->id, 'code' => 'RC-CHANGE'], ['name' => 'Plan Changed', 'category' => 'cancellation', 'description' => 'Production plan changed by management', 'is_active' => true]);

        // Cost Centers
        $ccProduction = CostCenter::updateOrCreate(['company_id' => $company->id, 'code' => 'CC-PROD'], ['name' => 'Production Department', 'description' => 'All production activities', 'is_active' => true]);
        $ccMaintenance = CostCenter::updateOrCreate(['company_id' => $company->id, 'code' => 'CC-MAINT'], ['name' => 'Maintenance Department', 'description' => 'Equipment maintenance', 'is_active' => true]);
        $ccQuality = CostCenter::updateOrCreate(['company_id' => $company->id, 'code' => 'CC-QUAL'], ['name' => 'Quality Department', 'description' => 'Quality control activities', 'is_active' => true]);
    }

    private function seedProductsAndBom(): void
    {
        $company = Company::where('code', 'DMFG')->first();
        $uomPcs = UnitOfMeasure::where('code', 'PCS')->first();
        $uomKg = UnitOfMeasure::where('code', 'KG')->first();
        $uomM = UnitOfMeasure::where('code', 'M')->first();
        $uomM2 = UnitOfMeasure::where('code' , 'M2')->first();
        $uomRoll = UnitOfMeasure::where('code', 'ROLL')->first();
        $uomBox = UnitOfMeasure::where('code', 'BOX')->first();
        $uomPair = UnitOfMeasure::where('code', 'PAIR')->first();

        $typeRaw = ProductType::where('code', 'RAW')->first();
        $typeComponent = ProductType::where('code', 'COMP')->first();
        $typeSemi = ProductType::where('code', 'SEMI')->first();
        $typeFinished = ProductType::where('code', 'FG')->first();
        $typeConsumable = ProductType::where('code', 'CONS')->first();
        $typePackaging = ProductType::where('code', 'PACK')->first();

        $catMaterial = ProductCategory::where('code', 'MAT')->first();
        $catComponent = ProductCategory::where('code', 'CMP')->first();
        $catSemi = ProductCategory::where('code', 'SEMI')->first();
        $catFinished = ProductCategory::where('code', 'FG')->first();
        $catConsumable = ProductCategory::where('code', 'CON')->first();
        $catPackaging = ProductCategory::where('code', 'PKG')->first();

        // Raw Materials
        $prodLeather = Product::updateOrCreate(
            ['company_id' => $company->id, 'code' => 'RM-LEATHER-001'],
            [
                'product_type_id' => $typeRaw->id, 'product_category_id' => $catMaterial->id,
                'uom_id' => $uomM2->id, 'name' => 'Genuine Leather Sheet', 'description' => 'Premium grade cowhide leather, 1.2mm thickness',
                'is_purchasable' => true, 'is_stockable' => true, 'is_batch_tracked' => true,
                'standard_cost' => 45.00, 'safety_stock' => 50, 'lead_time_days' => 14, 'status' => 'active',
            ]
        );

        $prodRubber = Product::updateOrCreate(
            ['company_id' => $company->id, 'code' => 'RM-RUBBER-001'],
            [
                'product_type_id' => $typeRaw->id, 'product_category_id' => $catMaterial->id,
                'uom_id' => $uomKg->id, 'name' => 'Rubber Compound', 'description' => 'Vulcanized rubber for sole production',
                'is_purchasable' => true, 'is_stockable' => true, 'is_batch_tracked' => true,
                'standard_cost' => 8.50, 'safety_stock' => 100, 'lead_time_days' => 7, 'status' => 'active',
            ]
        );

        $prodFabric = Product::updateOrCreate(
            ['company_id' => $company->id, 'code' => 'RM-FABRIC-001'],
            [
                'product_type_id' => $typeRaw->id, 'product_category_id' => $catMaterial->id,
                'uom_id' => $uomM->id, 'name' => 'Canvas Fabric Roll', 'description' => 'Heavy duty canvas, 12oz weight',
                'is_purchasable' => true, 'is_stockable' => true, 'is_batch_tracked' => true,
                'standard_cost' => 12.00, 'safety_stock' => 200, 'lead_time_days' => 10, 'status' => 'active',
            ]
        );

        $prodAdhesive = Product::updateOrCreate(
            ['company_id' => $company->id, 'code' => 'RM-ADHES-001'],
            [
                'product_type_id' => $typeRaw->id, 'product_category_id' => $catMaterial->id,
                'uom_id' => $uomKg->id, 'name' => 'Industrial Adhesive', 'description' => 'Water-based bonding adhesive',
                'is_purchasable' => true, 'is_stockable' => true, 'is_batch_tracked' => false,
                'standard_cost' => 5.25, 'safety_stock' => 30, 'lead_time_days' => 5, 'status' => 'active',
            ]
        );

        // Components
        $prodSole = Product::updateOrCreate(
            ['company_id' => $company->id, 'code' => 'CMP-SOLE-001'],
            [
                'product_type_id' => $typeComponent->id, 'product_category_id' => $catComponent->id,
                'uom_id' => $uomPair->id, 'name' => 'Rubber Sole Unit', 'description' => 'Pre-molded rubber sole',
                'is_manufacturable' => true, 'is_stockable' => true,
                'standard_cost' => 6.00, 'safety_stock' => 20, 'status' => 'active',
            ]
        );

        $prodInsole = Product::updateOrCreate(
            ['company_id' => $company->id, 'code' => 'CMP-INSOLE-001'],
            [
                'product_type_id' => $typeComponent->id, 'product_category_id' => $catComponent->id,
                'uom_id' => $uomPair->id, 'name' => 'Cushioned Insole', 'description' => 'Memory foam insole',
                'is_purchasable' => true, 'is_stockable' => true,
                'standard_cost' => 2.50, 'safety_stock' => 50, 'lead_time_days' => 3, 'status' => 'active',
            ]
        );

        $prodLace = Product::updateOrCreate(
            ['company_id' => $company->id, 'code' => 'CMP-LACE-001'],
            [
                'product_type_id' => $typeComponent->id, 'product_category_id' => $catComponent->id,
                'uom_id' => $uomPair->id, 'name' => 'Shoelace Pair', 'description' => 'Wax-coated cotton shoelace',
                'is_purchasable' => true, 'is_stockable' => true,
                'standard_cost' => 0.80, 'safety_stock' => 100, 'lead_time_days' => 3, 'status' => 'active',
            ]
        );

        // Semi-Finished
        $prodUpper = Product::updateOrCreate(
            ['company_id' => $company->id, 'code' => 'SF-UPPER-001'],
            [
                'product_type_id' => $typeSemi->id, 'product_category_id' => $catSemi->id,
                'uom_id' => $uomPair->id, 'name' => 'Shoe Upper Assembly', 'description' => 'Stitched leather upper',
                'is_manufacturable' => true, 'is_stockable' => true,
                'standard_cost' => 18.00, 'status' => 'active',
            ]
        );

        // Finished Goods
        $prodShoe = Product::updateOrCreate(
            ['company_id' => $company->id, 'code' => 'FG-SHOE-001'],
            [
                'product_type_id' => $typeFinished->id, 'product_category_id' => $catFinished->id,
                'uom_id' => $uomPair->id, 'name' => 'Classic Leather Shoe', 'description' => 'Handcrafted leather shoe, model CL-100',
                'is_manufacturable' => true, 'is_sellable' => true, 'is_stockable' => true, 'is_batch_tracked' => true,
                'standard_cost' => 35.00, 'safety_stock' => 10, 'lead_time_days' => 5, 'status' => 'active',
            ]
        );

        // Consumables
        $prodThread = Product::updateOrCreate(
            ['company_id' => $company->id, 'code' => 'CON-THREAD-001'],
            [
                'product_type_id' => $typeConsumable->id, 'product_category_id' => $catConsumable->id,
                'uom_id' => $uomRoll->id, 'name' => 'Nylon Thread Spool', 'description' => 'Industrial grade nylon thread',
                'is_purchasable' => true, 'is_stockable' => true,
                'standard_cost' => 3.00, 'safety_stock' => 20, 'lead_time_days' => 3, 'status' => 'active',
            ]
        );

        $prodGlue = Product::updateOrCreate(
            ['company_id' => $company->id, 'code' => 'CON-GLUE-001'],
            [
                'product_type_id' => $typeConsumable->id, 'product_category_id' => $catConsumable->id,
                'uom_id' => $uomKg->id, 'name' => 'Shoe Glue', 'description' => 'Strong bonding adhesive for shoe assembly',
                'is_purchasable' => true, 'is_stockable' => true,
                'standard_cost' => 4.50, 'safety_stock' => 10, 'lead_time_days' => 2, 'status' => 'active',
            ]
        );

        // Packaging
        $prodBoxPkg = Product::updateOrCreate(
            ['company_id' => $company->id, 'code' => 'PKG-BOX-001'],
            [
                'product_type_id' => $typePackaging->id, 'product_category_id' => $catPackaging->id,
                'uom_id' => $uomPcs->id, 'name' => 'Shoe Box', 'description' => 'Standard shoe packaging box',
                'is_purchasable' => true, 'is_stockable' => true,
                'standard_cost' => 1.20, 'safety_stock' => 50, 'lead_time_days' => 5, 'status' => 'active',
            ]
        );

        $prodTissue = Product::updateOrCreate(
            ['company_id' => $company->id, 'code' => 'PKG-TISS-001'],
            [
                'product_type_id' => $typePackaging->id, 'product_category_id' => $catPackaging->id,
                'uom_id' => $uomPcs->id, 'name' => 'Tissue Paper', 'description' => 'Acid-free tissue paper for wrapping',
                'is_purchasable' => true, 'is_stockable' => true,
                'standard_cost' => 0.15, 'safety_stock' => 200, 'lead_time_days' => 2, 'status' => 'active',
            ]
        );

        // ========================
        // BOM - Shoe Upper Assembly (Semi-Finished)
        // ========================
        $bomUpper = BomHeader::updateOrCreate(
            ['company_id' => $company->id, 'code' => 'BOM-UPPER-001'],
            [
                'product_id' => $prodUpper->id,
                'name' => 'Shoe Upper Assembly BOM',
                'description' => 'Bill of materials for leather shoe upper',
                'production_process_id' => ProductionProcess::where('code', 'PROC-CUT')->first()->id,
                'is_active' => true,
            ]
        );

        $bomUpperV1 = BomVersion::updateOrCreate(
            ['bom_header_id' => $bomUpper->id, 'version' => '1.0'],
            [
                'effective_date' => '2026-01-01',
                'approval_state' => 'approved',
                'approved_by' => 'Andi Wijaya',
                'approved_at' => now(),
                'is_default' => true,
                'notes' => 'Initial BOM version for shoe upper',
            ]
        );

        BomComponent::updateOrCreate(
            ['bom_version_id' => $bomUpperV1->id, 'product_id' => $prodLeather->id],
            ['uom_id' => $uomM2->id, 'quantity' => 0.8, 'scrap_percentage' => 10, 'yield_percentage' => 90, 'is_critical' => true, 'sort_order' => 1]
        );

        BomComponent::updateOrCreate(
            ['bom_version_id' => $bomUpperV1->id, 'product_id' => $prodThread->id],
            ['uom_id' => $uomRoll->id, 'quantity' => 0.1, 'scrap_percentage' => 5, 'yield_percentage' => 95, 'sort_order' => 2]
        );

        BomComponent::updateOrCreate(
            ['bom_version_id' => $bomUpperV1->id, 'product_id' => $prodAdhesive->id],
            ['uom_id' => $uomKg->id, 'quantity' => 0.05, 'scrap_percentage' => 0, 'yield_percentage' => 100, 'sort_order' => 3]
        );

        // Substitute for leather
        $leatherComponent = BomComponent::where('bom_version_id', $bomUpperV1->id)->where('product_id', $prodLeather->id)->first();
        BomSubstitute::updateOrCreate(
            ['bom_component_id' => $leatherComponent->id, 'product_id' => $prodFabric->id],
            ['uom_id' => $uomM->id, 'conversion_factor' => 1.0, 'is_preferred' => false]
        );

        // ========================
        // BOM - Finished Shoe (Finished Goods)
        // ========================
        $bomShoe = BomHeader::updateOrCreate(
            ['company_id' => $company->id, 'code' => 'BOM-SHOE-001'],
            [
                'product_id' => $prodShoe->id,
                'name' => 'Classic Leather Shoe BOM',
                'description' => 'Bill of materials for CL-100 shoe',
                'production_process_id' => ProductionProcess::where('code', 'PROC-ASM')->first()->id,
                'is_active' => true,
            ]
        );

        $bomShoeV1 = BomVersion::updateOrCreate(
            ['bom_header_id' => $bomShoe->id, 'version' => '1.0'],
            [
                'effective_date' => '2026-01-01',
                'approval_state' => 'approved',
                'approved_by' => 'Andi Wijaya',
                'approved_at' => now(),
                'is_default' => true,
                'notes' => 'Initial BOM for finished shoe assembly',
            ]
        );

        BomComponent::updateOrCreate(
            ['bom_version_id' => $bomShoeV1->id, 'product_id' => $prodUpper->id],
            ['uom_id' => $uomPair->id, 'quantity' => 1, 'scrap_percentage' => 2, 'yield_percentage' => 98, 'is_critical' => true, 'sort_order' => 1]
        );

        BomComponent::updateOrCreate(
            ['bom_version_id' => $bomShoeV1->id, 'product_id' => $prodSole->id],
            ['uom_id' => $uomPair->id, 'quantity' => 1, 'scrap_percentage' => 1, 'yield_percentage' => 99, 'is_critical' => true, 'sort_order' => 2]
        );

        BomComponent::updateOrCreate(
            ['bom_version_id' => $bomShoeV1->id, 'product_id' => $prodInsole->id],
            ['uom_id' => $uomPair->id, 'quantity' => 1, 'scrap_percentage' => 0, 'yield_percentage' => 100, 'sort_order' => 3]
        );

        BomComponent::updateOrCreate(
            ['bom_version_id' => $bomShoeV1->id, 'product_id' => $prodLace->id],
            ['uom_id' => $uomPair->id, 'quantity' => 1, 'scrap_percentage' => 0, 'yield_percentage' => 100, 'sort_order' => 4]
        );

        BomComponent::updateOrCreate(
            ['bom_version_id' => $bomShoeV1->id, 'product_id' => $prodGlue->id],
            ['uom_id' => $uomKg->id, 'quantity' => 0.15, 'scrap_percentage' => 5, 'yield_percentage' => 95, 'sort_order' => 5]
        );

        BomComponent::updateOrCreate(
            ['bom_version_id' => $bomShoeV1->id, 'product_id' => $prodBoxPkg->id],
            ['uom_id' => $uomPcs->id, 'quantity' => 1, 'scrap_percentage' => 0, 'yield_percentage' => 100, 'sort_order' => 6]
        );

        BomComponent::updateOrCreate(
            ['bom_version_id' => $bomShoeV1->id, 'product_id' => $prodTissue->id],
            ['uom_id' => $uomPcs->id, 'quantity' => 2, 'scrap_percentage' => 0, 'yield_percentage' => 100, 'sort_order' => 7]
        );
    }

    private function seedProductUoms(): void
    {
        $uomPcs = UnitOfMeasure::where('code', 'PCS')->first();
        $uomPair = UnitOfMeasure::where('code', 'PAIR')->first();
        $uomKg = UnitOfMeasure::where('code', 'KG')->first();
        $uomM = UnitOfMeasure::where('code', 'M')->first();
        $uomM2 = UnitOfMeasure::where('code', 'M2')->first();
        $uomRoll = UnitOfMeasure::where('code', 'ROLL')->first();
        $uomBox = UnitOfMeasure::where('code', 'BOX')->first();

        // Finished Goods: Classic Leather Shoe (base UOM: PAIR)
        $prodShoe = Product::where('code', 'FG-SHOE-001')->first();
        if ($prodShoe) {
            ProductUom::updateOrCreate(
                ['product_id' => $prodShoe->id, 'uom_id' => $uomPcs->id, 'usage_type' => 'sales'],
                ['conversion_factor' => 1, 'is_default' => true]
            );
            ProductUom::updateOrCreate(
                ['product_id' => $prodShoe->id, 'uom_id' => $uomBox->id, 'usage_type' => 'sales'],
                ['conversion_factor' => 12, 'is_default' => false]
            );
            ProductUom::updateOrCreate(
                ['product_id' => $prodShoe->id, 'uom_id' => $uomPcs->id, 'usage_type' => 'production'],
                ['conversion_factor' => 1, 'is_default' => true]
            );
        }

        // Raw Material: Leather (base UOM: M2)
        $prodLeather = Product::where('code', 'RM-LEATHER-001')->first();
        if ($prodLeather) {
            ProductUom::updateOrCreate(
                ['product_id' => $prodLeather->id, 'uom_id' => $uomM2->id, 'usage_type' => 'purchasing'],
                ['conversion_factor' => 1, 'is_default' => true]
            );
        }

        // Raw Material: Rubber (base UOM: KG)
        $prodRubber = Product::where('code', 'RM-RUBBER-001')->first();
        if ($prodRubber) {
            ProductUom::updateOrCreate(
                ['product_id' => $prodRubber->id, 'uom_id' => $uomKg->id, 'usage_type' => 'purchasing'],
                ['conversion_factor' => 1, 'is_default' => true]
            );
        }

        // Component: Sole (base UOM: PAIR)
        $prodSole = Product::where('code', 'CMP-SOLE-001')->first();
        if ($prodSole) {
            ProductUom::updateOrCreate(
                ['product_id' => $prodSole->id, 'uom_id' => $uomPcs->id, 'usage_type' => 'production'],
                ['conversion_factor' => 2, 'is_default' => false]
            );
        }

        // Consumable: Thread (base UOM: ROLL)
        $prodThread = Product::where('code', 'CON-THREAD-001')->first();
        if ($prodThread) {
            ProductUom::updateOrCreate(
                ['product_id' => $prodThread->id, 'uom_id' => $uomRoll->id, 'usage_type' => 'purchasing'],
                ['conversion_factor' => 1, 'is_default' => true]
            );
        }

        // Packaging: Box (base UOM: PCS)
        $prodBox = Product::where('code', 'PKG-BOX-001')->first();
        if ($prodBox) {
            ProductUom::updateOrCreate(
                ['product_id' => $prodBox->id, 'uom_id' => $uomBox->id, 'usage_type' => 'purchasing'],
                ['conversion_factor' => 12, 'is_default' => false]
            );
        }
    }

    private function seedRouting(): void
    {
        $company = Company::where('code', 'DMFG')->first();
        $wcCutting = WorkCenter::where('code', 'WC-CUT')->first();
        $wcAssembly = WorkCenter::where('code', 'WC-ASM')->first();
        $wcFinishing = WorkCenter::where('code', 'WC-FIN')->first();
        $machineCut1 = Machine::where('code', 'MCH-CUT-01')->first();
        $machineAsm1 = Machine::where('code', 'MCH-ASM-01')->first();
        $machineFin1 = Machine::where('code', 'MCH-FIN-01')->first();
        $prodShoe = Product::where('code', 'FG-SHOE-001')->first();
        $prodUpper = Product::where('code', 'SF-UPPER-001')->first();
        $uomPcs = UnitOfMeasure::where('code', 'PCS')->first();
        $processCutting = ProductionProcess::where('code', 'PROC-CUT')->first();
        $processAssembly = ProductionProcess::where('code', 'PROC-ASM')->first();
        $processFinishing = ProductionProcess::where('code', 'PROC-FIN')->first();

        // Routing for Shoe Upper
        $rtgUpper = RoutingHeader::updateOrCreate(
            ['company_id' => $company->id, 'code' => 'RTG-UPPER-001'],
            [
                'product_id' => $prodUpper->id,
                'name' => 'Shoe Upper Routing',
                'description' => 'Production routing for leather shoe upper',
                'is_active' => true,
            ]
        );

        $rtgUpperV1 = RoutingVersion::updateOrCreate(
            ['routing_header_id' => $rtgUpper->id, 'version' => '1.0'],
            [
                'effective_date' => '2026-01-01',
                'approval_state' => 'approved',
                'approved_by' => 'Andi Wijaya',
                'approved_at' => now(),
                'is_default' => true,
            ]
        );

        $opUpperCut = RoutingOperation::updateOrCreate(
            ['routing_version_id' => $rtgUpperV1->id, 'sequence' => 10],
            [
                'code' => 'OP-CUT', 'name' => 'Cut Leather',
                'work_center_id' => $wcCutting->id, 'machine_id' => $machineCut1->id,
                'production_process_id' => $processCutting->id,
                'setup_time_minutes' => 15, 'run_time_minutes' => 2, 'queue_time_minutes' => 5,
                'labor_required' => 1, 'machine_required' => 1,
                'standard_output' => 30, 'output_uom_id' => $uomPcs->id,
                'scrap_percentage' => 10, 'quality_checkpoint' => 'Check cut dimensions and leather quality',
            ]
        );

        $opUpperStitch = RoutingOperation::updateOrCreate(
            ['routing_version_id' => $rtgUpperV1->id, 'sequence' => 20],
            [
                'code' => 'OP-STITCH', 'name' => 'Stitch Upper',
                'work_center_id' => $wcAssembly->id, 'machine_id' => $machineAsm1->id,
                'production_process_id' => $processAssembly->id,
                'setup_time_minutes' => 10, 'run_time_minutes' => 3, 'queue_time_minutes' => 5,
                'labor_required' => 1, 'machine_required' => 1,
                'standard_output' => 20, 'output_uom_id' => $uomPcs->id,
                'scrap_percentage' => 2, 'quality_checkpoint' => 'Check stitch quality and alignment',
            ]
        );

        RoutingOperationDependency::updateOrCreate(
            ['routing_operation_id' => $opUpperStitch->id, 'depends_on_operation_id' => $opUpperCut->id],
            ['dependency_type' => 'finish_to_start', 'lag_time_minutes' => 5]
        );

        // Routing for Finished Shoe
        $rtgShoe = RoutingHeader::updateOrCreate(
            ['company_id' => $company->id, 'code' => 'RTG-SHOE-001'],
            [
                'product_id' => $prodShoe->id,
                'name' => 'Classic Leather Shoe Routing',
                'description' => 'Production routing for CL-100 shoe',
                'is_active' => true,
            ]
        );

        $rtgShoeV1 = RoutingVersion::updateOrCreate(
            ['routing_header_id' => $rtgShoe->id, 'version' => '1.0'],
            [
                'effective_date' => '2026-01-01',
                'approval_state' => 'approved',
                'approved_by' => 'Andi Wijaya',
                'approved_at' => now(),
                'is_default' => true,
            ]
        );

        $opShoePrep = RoutingOperation::updateOrCreate(
            ['routing_version_id' => $rtgShoeV1->id, 'sequence' => 10],
            [
                'code' => 'OP-PREP', 'name' => 'Prepare Components',
                'work_center_id' => $wcAssembly->id,
                'production_process_id' => $processAssembly->id,
                'setup_time_minutes' => 5, 'run_time_minutes' => 1, 'queue_time_minutes' => 0,
                'labor_required' => 1,
                'standard_output' => 50, 'output_uom_id' => $uomPcs->id,
            ]
        );

        $opShoeAsm = RoutingOperation::updateOrCreate(
            ['routing_version_id' => $rtgShoeV1->id, 'sequence' => 20],
            [
                'code' => 'OP-ASM', 'name' => 'Assemble Shoe',
                'work_center_id' => $wcAssembly->id, 'machine_id' => $machineAsm1->id,
                'production_process_id' => $processAssembly->id,
                'setup_time_minutes' => 10, 'run_time_minutes' => 5, 'queue_time_minutes' => 5,
                'labor_required' => 2, 'machine_required' => 1,
                'standard_output' => 12, 'output_uom_id' => $uomPcs->id,
                'quality_checkpoint' => 'Check assembly integrity and alignment',
            ]
        );

        $opShoeFinish = RoutingOperation::updateOrCreate(
            ['routing_version_id' => $rtgShoeV1->id, 'sequence' => 30],
            [
                'code' => 'OP-FINISH', 'name' => 'Finish and Polish',
                'work_center_id' => $wcFinishing->id, 'machine_id' => $machineFin1->id,
                'production_process_id' => $processFinishing->id,
                'setup_time_minutes' => 5, 'run_time_minutes' => 3, 'queue_time_minutes' => 5,
                'labor_required' => 1, 'machine_required' => 1,
                'standard_output' => 15, 'output_uom_id' => $uomPcs->id,
                'quality_checkpoint' => 'Final visual inspection and polish quality',
            ]
        );

        RoutingOperationDependency::updateOrCreate(
            ['routing_operation_id' => $opShoeAsm->id, 'depends_on_operation_id' => $opShoePrep->id],
            ['dependency_type' => 'finish_to_start', 'lag_time_minutes' => 5]
        );

        RoutingOperationDependency::updateOrCreate(
            ['routing_operation_id' => $opShoeFinish->id, 'depends_on_operation_id' => $opShoeAsm->id],
            ['dependency_type' => 'finish_to_start', 'lag_time_minutes' => 10]
        );
    }

    private function seedPlanning(): void
    {
        $company = Company::where('code', 'DMFG')->first();
        $plantMain = Plant::where('code', 'PLT-MAIN')->first();
        $prodShoe = Product::where('code', 'FG-SHOE-001')->first();
        $prodLeather = Product::where('code', 'RM-LEATHER-001')->first();
        $prodSole = Product::where('code', 'CMP-SOLE-001')->first();
        $uomPair = UnitOfMeasure::where('code', 'PAIR')->first();
        $uomM2 = UnitOfMeasure::where('code', 'M2')->first();

        // Demand
        $demand = Demand::updateOrCreate(
            ['company_id' => $company->id, 'document_number' => 'DEM-2026-000001'],
            [
                'plant_id' => $plantMain->id,
                'source_type' => 'manual',
                'source_number' => 'SO-2026-001',
                'demand_date' => '2026-08-01',
                'required_date' => '2026-08-30',
                'priority' => 'high',
                'status' => 'confirmed',
                'notes' => 'Customer order for August delivery',
            ]
        );

        DemandLine::updateOrCreate(
            ['demand_id' => $demand->id, 'product_id' => $prodShoe->id],
            ['uom_id' => $uomPair->id, 'quantity' => 200, 'fulfilled_quantity' => 0, 'required_date' => '2026-08-30']
        );

        // MPS
        $mps = MasterProductionSchedule::updateOrCreate(
            ['company_id' => $company->id, 'document_number' => 'MPS-2026-000001'],
            [
                'plant_id' => $plantMain->id,
                'plan_date' => '2026-08-01',
                'from_date' => '2026-08-01',
                'to_date' => '2026-08-31',
                'status' => 'confirmed',
                'notes' => 'August 2026 production schedule',
            ]
        );

        DB::table('mps_lines')->updateOrInsert(
            ['master_production_schedule_id' => $mps->id, 'product_id' => $prodShoe->id],
            [
                'demand_id' => $demand->id,
                'uom_id' => $uomPair->id,
                'planned_date' => '2026-08-15',
                'demand_quantity' => 200,
                'planned_quantity' => 200,
                'available_quantity' => 0,
                'projected_balance' => 200,
                'status' => 'planned',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Material Requirements
        $mrLeather = MaterialRequirement::updateOrCreate(
            ['company_id' => $company->id, 'product_id' => $prodLeather->id],
            [
                'plant_id' => $plantMain->id,
                'uom_id' => $uomM2->id,
                'source_type' => 'mps',
                'source_id' => $mps->id,
                'required_date' => '2026-08-10',
                'required_quantity' => 160, // 200 pairs * 0.8m² per pair
                'available_quantity' => 80,
                'planned_receipt_quantity' => 120,
                'planned_release_quantity' => 120,
                'shortage_quantity' => 0,
                'safety_stock' => 50,
                'lead_time_days' => 14,
                'status' => 'planned',
                'notes' => 'Leather requirement for shoe upper production',
            ]
        );

        $mrSole = MaterialRequirement::updateOrCreate(
            ['company_id' => $company->id, 'product_id' => $prodSole->id],
            [
                'plant_id' => $plantMain->id,
                'uom_id' => $uomPair->id,
                'source_type' => 'mps',
                'source_id' => $mps->id,
                'required_date' => '2026-08-10',
                'required_quantity' => 200,
                'available_quantity' => 150,
                'planned_receipt_quantity' => 50,
                'planned_release_quantity' => 50,
                'shortage_quantity' => 0,
                'safety_stock' => 20,
                'lead_time_days' => 3,
                'status' => 'ordered',
                'notes' => 'Sole requirement from BOM explosion',
            ]
        );

        // Planned Orders
        PlannedOrder::updateOrCreate(
            ['company_id' => $company->id, 'material_requirement_id' => $mrLeather->id],
            [
                'plant_id' => $plantMain->id,
                'product_id' => $prodLeather->id,
                'uom_id' => $uomM2->id,
                'order_type' => 'purchase',
                'planned_quantity' => 120,
                'planned_release_date' => '2026-07-27',
                'planned_receipt_date' => '2026-08-10',
                'lead_time_days' => 14,
                'status' => 'firm',
            ]
        );
    }

    private function seedProduction(): void
    {
        $company = Company::where('code', 'DMFG')->first();
        $plantMain = Plant::where('code', 'PLT-MAIN')->first();
        $prodShoe = Product::where('code', 'FG-SHOE-001')->first();
        $prodUpper = Product::where('code', 'SF-UPPER-001')->first();
        $prodLeather = Product::where('code', 'RM-LEATHER-001')->first();
        $prodGlue = Product::where('code', 'CON-GLUE-001')->first();
        $prodBoxPkg = Product::where('code', 'PKG-BOX-001')->first();
        $bomShoeV1 = BomVersion::where('version', '1.0')->whereHas('bomHeader', fn($q) => $q->where('code', 'BOM-SHOE-001'))->first();
        $rtgShoeV1 = RoutingVersion::where('version', '1.0')->whereHas('routingHeader', fn($q) => $q->where('code', 'RTG-SHOE-001'))->first();
        $wcAssembly = WorkCenter::where('code', 'WC-ASM')->first();
        $wcFinishing = WorkCenter::where('code', 'WC-FIN')->first();
        $machineAsm1 = Machine::where('code', 'MCH-ASM-01')->first();
        $empOperator = Employee::where('code', 'EMP-001')->first();
        $whWip = Warehouse::where('code', 'WH-WIP')->first();
        $whFg = Warehouse::where('code', 'WH-FG')->first();
        $whRaw = Warehouse::where('code', 'WH-RAW')->first();
        $uomPair = UnitOfMeasure::where('code', 'PAIR')->first();
        $uomKg = UnitOfMeasure::where('code', 'KG')->first();
        $uomPcs = UnitOfMeasure::where('code', 'PCS')->first();
        $uomM2 = UnitOfMeasure::where('code', 'M2')->first();
        $demand = Demand::where('document_number', 'DEM-2026-000001')->first();

        // Production Order
        $po = ProductionOrder::updateOrCreate(
            ['company_id' => $company->id, 'document_number' => 'MO-2026-000001'],
            [
                'plant_id' => $plantMain->id,
                'product_id' => $prodShoe->id,
                'bom_version_id' => $bomShoeV1->id,
                'routing_version_id' => $rtgShoeV1->id,
                'warehouse_id' => $whFg->id,
                'demand_id' => $demand->id,
                'uom_id' => $uomPair->id,
                'planned_quantity' => 200,
                'confirmed_quantity' => 0,
                'produced_quantity' => 0,
                'rejected_quantity' => 0,
                'scrap_quantity' => 0,
                'planned_start_date' => '2026-08-10',
                'planned_finish_date' => '2026-08-20',
                'priority' => 'high',
                'status' => 'released',
                'notes' => 'Production order for customer August order',
            ]
        );

        // Work Orders
        $wo1 = WorkOrder::updateOrCreate(
            ['company_id' => $company->id, 'document_number' => 'WO-2026-000001'],
            [
                'production_order_id' => $po->id,
                'work_center_id' => $wcAssembly->id,
                'machine_id' => $machineAsm1->id,
                'operator_id' => $empOperator->id,
                'routing_operation_id' => RoutingOperation::where('code', 'OP-ASM')->first()->id,
                'sequence' => 10,
                'planned_quantity' => 200,
                'actual_quantity' => 0,
                'setup_time_minutes' => 10,
                'run_time_minutes' => 5,
                'status' => 'in_progress',
                'started_at' => now(),
                'notes' => 'Assembly operation',
            ]
        );

        $wo2 = WorkOrder::updateOrCreate(
            ['company_id' => $company->id, 'document_number' => 'WO-2026-000002'],
            [
                'production_order_id' => $po->id,
                'work_center_id' => $wcFinishing->id,
                'routing_operation_id' => RoutingOperation::where('code', 'OP-FINISH')->first()->id,
                'sequence' => 20,
                'planned_quantity' => 200,
                'actual_quantity' => 0,
                'setup_time_minutes' => 5,
                'run_time_minutes' => 3,
                'status' => 'pending',
                'notes' => 'Finishing operation - waiting for assembly',
            ]
        );

        // Material Consumption (Issue for production)
        $mcLeather = MaterialConsumption::updateOrCreate(
            ['company_id' => $company->id, 'document_number' => 'MI-2026-000001'],
            [
                'production_order_id' => $po->id,
                'work_order_id' => $wo1->id,
                'product_id' => $prodLeather->id,
                'uom_id' => $uomM2->id,
                'warehouse_id' => $whRaw->id,
                'planned_quantity' => 160,
                'actual_quantity' => 160,
                'batch_number' => 'BATCH-LEA-001',
                'issue_date' => '2026-08-10',
                'status' => 'posted',
                'notes' => 'Leather issued for shoe upper production',
            ]
        );

        $mcGlue = MaterialConsumption::updateOrCreate(
            ['company_id' => $company->id, 'document_number' => 'MI-2026-000002'],
            [
                'production_order_id' => $po->id,
                'work_order_id' => $wo1->id,
                'product_id' => $prodGlue->id,
                'uom_id' => $uomKg->id,
                'warehouse_id' => $whRaw->id,
                'planned_quantity' => 30,
                'actual_quantity' => 32,
                'issue_date' => '2026-08-10',
                'status' => 'posted',
                'notes' => 'Adhesive issued for bonding',
            ]
        );

        // Stock Movements for material issues
        StockMovement::updateOrCreate(
            ['company_id' => $company->id, 'document_number' => 'SM-2026-000001'],
            [
                'product_id' => $prodLeather->id,
                'warehouse_id' => $whRaw->id,
                'uom_id' => $uomM2->id,
                'movement_type' => 'material_issue',
                'quantity' => -160,
                'unit_cost' => 45.00,
                'total_cost' => -7200.00,
                'batch_number' => 'BATCH-LEA-001',
                'transaction_date' => '2026-08-10',
                'source_type' => MaterialConsumption::class,
                'source_id' => $mcLeather->id,
                'reference_number' => $mcLeather->document_number,
                'notes' => 'Leather issue for production order MO-2026-000001',
            ]
        );

        StockMovement::updateOrCreate(
            ['company_id' => $company->id, 'document_number' => 'SM-2026-000002'],
            [
                'product_id' => $prodGlue->id,
                'warehouse_id' => $whRaw->id,
                'uom_id' => $uomKg->id,
                'movement_type' => 'material_issue',
                'quantity' => -32,
                'unit_cost' => 4.50,
                'total_cost' => -144.00,
                'transaction_date' => '2026-08-10',
                'source_type' => MaterialConsumption::class,
                'source_id' => $mcGlue->id,
                'reference_number' => $mcGlue->document_number,
                'notes' => 'Glue issue for production order MO-2026-000001',
            ]
        );

        // Stock Balance updates
        StockBalance::updateOrCreate(
            ['company_id' => $company->id, 'product_id' => $prodLeather->id, 'warehouse_id' => $whRaw->id, 'location_id' => null, 'batch_number' => 'BATCH-LEA-001'],
            ['uom_id' => $uomM2->id, 'quantity' => 80, 'available_quantity' => 80, 'average_cost' => 45.00, 'total_value' => 3600.00, 'last_movement_at' => now()]
        );

        StockBalance::updateOrCreate(
            ['company_id' => $company->id, 'product_id' => $prodGlue->id, 'warehouse_id' => $whRaw->id, 'location_id' => null, 'batch_number' => null],
            ['uom_id' => $uomKg->id, 'quantity' => 68, 'available_quantity' => 68, 'average_cost' => 4.50, 'total_value' => 306.00, 'last_movement_at' => now()]
        );

        // Scrap Record
        Scrap::updateOrCreate(
            ['company_id' => $company->id, 'production_order_id' => $po->id],
            [
                'work_order_id' => $wo1->id,
                'product_id' => $prodLeather->id,
                'reason_code_id' => ReasonCode::where('code', 'RS-DEFECT')->first()->id,
                'uom_id' => $uomM2->id,
                'quantity' => 5,
                'estimated_cost' => 225.00,
                'scrap_date' => '2026-08-11',
                'notes' => 'Leather defect during cutting',
            ]
        );
    }

    private function seedInventory(): void
    {
        $company = Company::where('code', 'DMFG')->first();
        $prodShoe = Product::where('code', 'FG-SHOE-001')->first();
        $prodLeather = Product::where('code', 'RM-LEATHER-001')->first();
        $prodSole = Product::where('code', 'CMP-SOLE-001')->first();
        $whFg = Warehouse::where('code', 'WH-FG')->first();
        $whRaw = Warehouse::where('code', 'WH-RAW')->first();
        $uomPair = UnitOfMeasure::where('code', 'PAIR')->first();
        $uomM2 = UnitOfMeasure::where('code', 'M2')->first();
        $po = ProductionOrder::where('document_number', 'MO-2026-000001')->first();

        // Production Result
        $pr = ProductionResult::updateOrCreate(
            ['company_id' => $company->id, 'document_number' => 'PR-2026-000001'],
            [
                'production_order_id' => $po->id,
                'product_id' => $prodShoe->id,
                'uom_id' => $uomPair->id,
                'warehouse_id' => $whFg->id,
                'good_quantity' => 180,
                'rejected_quantity' => 10,
                'scrap_quantity' => 5,
                'batch_number' => 'BATCH-FG-001',
                'result_date' => '2026-08-18',
                'status' => 'posted',
                'notes' => 'First batch production result',
            ]
        );

        // Stock Movement for finished goods receipt
        StockMovement::updateOrCreate(
            ['company_id' => $company->id, 'document_number' => 'SM-2026-000003'],
            [
                'product_id' => $prodShoe->id,
                'warehouse_id' => $whFg->id,
                'uom_id' => $uomPair->id,
                'movement_type' => 'finished_goods_receipt',
                'quantity' => 180,
                'unit_cost' => 35.00,
                'total_cost' => 6300.00,
                'batch_number' => 'BATCH-FG-001',
                'transaction_date' => '2026-08-18',
                'source_type' => ProductionResult::class,
                'source_id' => $pr->id,
                'reference_number' => $pr->document_number,
                'notes' => 'Finished goods receipt from production',
            ]
        );

        // Stock Balance for finished goods
        StockBalance::updateOrCreate(
            ['company_id' => $company->id, 'product_id' => $prodShoe->id, 'warehouse_id' => $whFg->id, 'location_id' => null, 'batch_number' => 'BATCH-FG-001'],
            ['uom_id' => $uomPair->id, 'quantity' => 180, 'available_quantity' => 180, 'average_cost' => 35.00, 'total_value' => 6300.00, 'last_movement_at' => now()]
        );

        // Opening balance for raw materials
        StockMovement::updateOrCreate(
            ['company_id' => $company->id, 'document_number' => 'SM-2026-000010'],
            [
                'product_id' => $prodSole->id,
                'warehouse_id' => $whRaw->id,
                'uom_id' => $uomPair->id,
                'movement_type' => 'opening_balance',
                'quantity' => 150,
                'unit_cost' => 6.00,
                'total_cost' => 900.00,
                'transaction_date' => '2026-08-01',
                'reference_number' => 'OPENING-BAL-2026',
                'notes' => 'Opening balance for sole inventory',
            ]
        );

        StockBalance::updateOrCreate(
            ['company_id' => $company->id, 'product_id' => $prodSole->id, 'warehouse_id' => $whRaw->id, 'location_id' => null, 'batch_number' => null],
            ['uom_id' => $uomPair->id, 'quantity' => 150, 'available_quantity' => 150, 'average_cost' => 6.00, 'total_value' => 900.00, 'last_movement_at' => now()]
        );
    }

    private function seedQuality(): void
    {
        $company = Company::where('code', 'DMFG')->first();
        $prodShoe = Product::where('code', 'FG-SHOE-001')->first();
        $empInspector = Employee::where('code', 'EMP-002')->first();
        $po = ProductionOrder::where('document_number', 'MO-2026-000001')->first();
        $pr = ProductionResult::where('document_number', 'PR-2026-000001')->first();
        $uomPair = UnitOfMeasure::where('code', 'PAIR')->first();

        // Quality Inspection
        $qi = QualityInspection::updateOrCreate(
            ['company_id' => $company->id, 'document_number' => 'QI-2026-000001'],
            [
                'product_id' => $prodShoe->id,
                'inspector_id' => $empInspector->id,
                'inspection_type' => 'final',
                'source_type' => ProductionResult::class,
                'source_id' => $pr->id,
                'source_document_number' => $pr->document_number,
                'production_order_id' => $po->id,
                'batch_number' => 'BATCH-FG-001',
                'quantity_inspected' => 180,
                'quantity_accepted' => 170,
                'quantity_rejected' => 10,
                'inspection_date' => '2026-08-19',
                'result' => 'conditional',
                'status' => 'completed',
                'notes' => 'Final inspection - some units need rework',
            ]
        );

        // Inspection Results
        InspectionResult::updateOrCreate(
            ['quality_inspection_id' => $qi->id, 'parameter_name' => 'Stitch Quality'],
            ['specification' => 'No loose threads, even spacing', 'actual_value' => '175/180 pass', 'unit' => 'pcs', 'result' => 'pass', 'sort_order' => 1]
        );

        InspectionResult::updateOrCreate(
            ['quality_inspection_id' => $qi->id, 'parameter_name' => 'Sole Bonding Strength'],
            ['specification' => '> 15 N/cm', 'actual_value' => '18.5 N/cm', 'unit' => 'N/cm', 'result' => 'pass', 'sort_order' => 2]
        );

        InspectionResult::updateOrCreate(
            ['quality_inspection_id' => $qi->id, 'parameter_name' => 'Visual Appearance'],
            ['specification' => 'No scratches, stains, or deformation', 'actual_value' => '10 units with minor scratches', 'unit' => null, 'result' => 'fail', 'sort_order' => 3]
        );

        InspectionResult::updateOrCreate(
            ['quality_inspection_id' => $qi->id, 'parameter_name' => 'Size Tolerance'],
            ['specification' => '+/- 2mm', 'actual_value' => 'All within tolerance', 'unit' => 'mm', 'result' => 'pass', 'sort_order' => 4]
        );

        // Non-Conformance
        $nc = NonConformance::updateOrCreate(
            ['company_id' => $company->id, 'document_number' => 'NC-2026-000001'],
            [
                'quality_inspection_id' => $qi->id,
                'production_order_id' => $po->id,
                'product_id' => $prodShoe->id,
                'reason_code_id' => ReasonCode::where('code', 'RQ-FAIL')->first()->id,
                'severity' => 'medium',
                'description' => '10 units with visible scratches on leather upper after finishing',
                'disposition' => 'rework',
                'affected_quantity' => 10,
                'estimated_cost' => 150.00,
                'status' => 'open',
                'root_cause' => 'Polishing machine pad worn out, causing uneven surface contact',
                'corrective_action' => 'Replace polishing pad and recalibrate machine',
                'target_date' => '2026-08-25',
            ]
        );

        // Rework Order
        ReworkOrder::updateOrCreate(
            ['company_id' => $company->id, 'document_number' => 'RW-2026-000001'],
            [
                'non_conformance_id' => $nc->id,
                'production_order_id' => $po->id,
                'product_id' => $prodShoe->id,
                'work_center_id' => WorkCenter::where('code', 'WC-FIN')->first()->id,
                'uom_id' => $uomPair->id,
                'quantity' => 10,
                'reworked_quantity' => 0,
                'scrapped_quantity' => 0,
                'description' => 'Re-polish scratched shoes',
                'status' => 'draft',
            ]
        );
    }

    private function seedMaintenance(): void
    {
        $company = Company::where('code', 'DMFG')->first();
        $machineCut1 = Machine::where('code', 'MCH-CUT-01')->first();
        $wcCutting = WorkCenter::where('code', 'WC-CUT')->first();
        $empTechnician = Employee::where('code', 'EMP-004')->first();

        // Maintenance Schedule
        $mSchedule = MaintenanceSchedule::updateOrCreate(
            ['company_id' => $company->id, 'code' => 'MS-CUT-01'],
            [
                'machine_id' => $machineCut1->id,
                'work_center_id' => $wcCutting->id,
                'name' => 'CNC Cutter Monthly Maintenance',
                'frequency' => 'monthly',
                'interval_value' => 1,
                'last_performed_date' => '2026-07-15',
                'next_due_date' => '2026-08-15',
                'estimated_duration_hours' => 4,
                'estimated_cost' => 500.00,
                'is_active' => true,
            ]
        );

        // Maintenance Order
        $mOrder = MaintenanceOrder::updateOrCreate(
            ['company_id' => $company->id, 'document_number' => 'MWO-2026-000001'],
            [
                'maintenance_schedule_id' => $mSchedule->id,
                'machine_id' => $machineCut1->id,
                'work_center_id' => $wcCutting->id,
                'assigned_to' => $empTechnician->id,
                'maintenance_type' => 'preventive',
                'description' => 'Monthly preventive maintenance for CNC Cutter 01',
                'scheduled_date' => '2026-08-15',
                'status' => 'scheduled',
            ]
        );

        // Maintenance History (previous completed maintenance)
        MaintenanceHistory::updateOrCreate(
            ['company_id' => $company->id, 'maintenance_order_id' => null, 'machine_id' => $machineCut1->id],
            [
                'technician_id' => $empTechnician->id,
                'maintenance_type' => 'preventive',
                'description' => 'Monthly maintenance - July 2026',
                'performed_actions' => 'Lubricated moving parts, checked blade alignment, cleaned filters, replaced cutting blade',
                'parts_replaced' => 'Cutting blade (part# BL-3000)',
                'actual_duration_hours' => 3.5,
                'actual_cost' => 420.00,
                'result' => 'completed',
                'performed_date' => '2026-07-15',
            ]
        );

        // Downtime Record
        DowntimeRecord::updateOrCreate(
            ['company_id' => $company->id, 'machine_id' => $machineCut1->id],
            [
                'work_order_id' => null,
                'maintenance_order_id' => $mOrder->id,
                'reason_code_id' => ReasonCode::where('code', 'RD-BREAK')->first()->id,
                'downtime_type' => 'planned',
                'started_at' => now()->subDays(2)->setTime(8, 0),
                'ended_at' => now()->subDays(2)->setTime(12, 0),
                'duration_minutes' => 240,
                'description' => 'Scheduled maintenance downtime',
            ]
        );
    }

    private function seedCosting(): void
    {
        $company = Company::where('code', 'DMFG')->first();
        $prodShoe = Product::where('code', 'FG-SHOE-001')->first();
        $bomShoeV1 = BomVersion::where('version', '1.0')->whereHas('bomHeader', fn($q) => $q->where('code', 'BOM-SHOE-001'))->first();
        $po = ProductionOrder::where('document_number', 'MO-2026-000001')->first();
        $ccProduction = CostCenter::where('code', 'CC-PROD')->first();

        // Product Cost (Standard Cost)
        ProductCost::updateOrCreate(
            ['company_id' => $company->id, 'product_id' => $prodShoe->id, 'version' => '1.0'],
            [
                'bom_version_id' => $bomShoeV1->id,
                'effective_date' => '2026-01-01',
                'material_cost' => 22.50,
                'labor_cost' => 6.50,
                'machine_cost' => 3.50,
                'overhead_cost' => 2.50,
                'total_cost' => 35.00,
                'unit_cost' => 35.00,
                'cost_type' => 'standard',
                'is_current' => true,
                'notes' => 'Standard cost based on BOM v1.0',
            ]
        );

        // Production Cost Transactions
        ProductionCostTransaction::updateOrCreate(
            ['company_id' => $company->id, 'production_order_id' => $po->id, 'cost_type' => 'material'],
            [
                'cost_center_id' => $ccProduction->id,
                'amount' => 4500.00,
                'quantity' => 200,
                'rate' => 22.50,
                'description' => 'Material cost for 200 pairs (leather, sole, insole, etc.)',
                'transaction_date' => '2026-08-10',
            ]
        );

        ProductionCostTransaction::updateOrCreate(
            ['company_id' => $company->id, 'production_order_id' => $po->id, 'cost_type' => 'labor'],
            [
                'cost_center_id' => $ccProduction->id,
                'amount' => 1300.00,
                'quantity' => 200,
                'rate' => 6.50,
                'description' => 'Labor cost for assembly and finishing',
                'transaction_date' => '2026-08-18',
            ]
        );

        ProductionCostTransaction::updateOrCreate(
            ['company_id' => $company->id, 'production_order_id' => $po->id, 'cost_type' => 'machine'],
            [
                'cost_center_id' => $ccProduction->id,
                'amount' => 700.00,
                'quantity' => 200,
                'rate' => 3.50,
                'description' => 'Machine usage cost',
                'transaction_date' => '2026-08-18',
            ]
        );

        // Cost Variance
        CostVariance::updateOrCreate(
            ['company_id' => $company->id, 'production_order_id' => $po->id, 'variance_type' => 'material_usage'],
            [
                'standard_amount' => 4500.00,
                'actual_amount' => 4644.00, // extra leather waste
                'variance_amount' => 144.00,
                'variance_percentage' => 3.2,
                'notes' => 'Higher material usage due to leather defects',
            ]
        );
    }

    private function seedSettings(): void
    {
        $company = Company::where('code', 'DMFG')->first();

        // Numbering Sequences
        NumberingSequence::updateOrCreate(
            ['company_id' => $company->id, 'document_type' => 'production_order'],
            ['prefix' => 'MO', 'include_year' => true, 'include_month' => true, 'padding' => 6, 'current_sequence' => 1, 'is_active' => true]
        );

        NumberingSequence::updateOrCreate(
            ['company_id' => $company->id, 'document_type' => 'work_order'],
            ['prefix' => 'WO', 'include_year' => true, 'include_month' => true, 'padding' => 6, 'current_sequence' => 2, 'is_active' => true]
        );

        NumberingSequence::updateOrCreate(
            ['company_id' => $company->id, 'document_type' => 'material_consumption'],
            ['prefix' => 'MI', 'include_year' => true, 'include_month' => true, 'padding' => 6, 'current_sequence' => 2, 'is_active' => true]
        );

        NumberingSequence::updateOrCreate(
            ['company_id' => $company->id, 'document_type' => 'stock_movement'],
            ['prefix' => 'SM', 'include_year' => true, 'include_month' => true, 'padding' => 6, 'current_sequence' => 10, 'is_active' => true]
        );

        NumberingSequence::updateOrCreate(
            ['company_id' => $company->id, 'document_type' => 'quality_inspection'],
            ['prefix' => 'QI', 'include_year' => true, 'include_month' => true, 'padding' => 6, 'current_sequence' => 1, 'is_active' => true]
        );

        NumberingSequence::updateOrCreate(
            ['company_id' => $company->id, 'document_type' => 'production_result'],
            ['prefix' => 'PR', 'include_year' => true, 'include_month' => true, 'padding' => 6, 'current_sequence' => 1, 'is_active' => true]
        );

        // Status Definitions for Production Order
        $statuses = [
            ['entity_type' => 'production_order', 'code' => 'draft', 'label' => 'Draft', 'color' => '#6b7280', 'sort_order' => 1, 'is_default' => true],
            ['entity_type' => 'production_order', 'code' => 'planned', 'label' => 'Planned', 'color' => '#3b82f6', 'sort_order' => 2],
            ['entity_type' => 'production_order', 'code' => 'released', 'label' => 'Released', 'color' => '#f59e0b', 'sort_order' => 3],
            ['entity_type' => 'production_order', 'code' => 'in_progress', 'label' => 'In Progress', 'color' => '#10b981', 'sort_order' => 4],
            ['entity_type' => 'production_order', 'code' => 'completed', 'label' => 'Completed', 'color' => '#059669', 'sort_order' => 5],
            ['entity_type' => 'production_order', 'code' => 'closed', 'label' => 'Closed', 'color' => '#374151', 'sort_order' => 6, 'is_terminal' => true],
            ['entity_type' => 'production_order', 'code' => 'cancelled', 'label' => 'Cancelled', 'color' => '#ef4444', 'sort_order' => 7, 'is_terminal' => true],
        ];

        foreach ($statuses as $status) {
            StatusDefinition::updateOrCreate(
                ['company_id' => $company->id, 'entity_type' => $status['entity_type'], 'code' => $status['code']],
                $status + ['is_active' => true]
            );
        }

        // Custom Field Definitions
        CustomFieldDefinition::updateOrCreate(
            ['company_id' => $company->id, 'entity_type' => 'production_order', 'field_name' => 'priority_level'],
            ['label' => 'Priority Level', 'field_type' => 'select', 'options' => ['Low', 'Normal', 'High', 'Urgent'], 'is_required' => false, 'sort_order' => 1, 'is_active' => true]
        );

        CustomFieldDefinition::updateOrCreate(
            ['company_id' => $company->id, 'entity_type' => 'product', 'field_name' => 'country_of_origin'],
            ['label' => 'Country of Origin', 'field_type' => 'text', 'is_required' => false, 'sort_order' => 2, 'is_active' => true]
        );
    }
}
