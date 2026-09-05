<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Super\{RoleController, PermissionController, UserManageController, UserController};
use App\Http\Controllers\Admin\{

    ProductController,
    ProductPriceController,
    HolidayController,
    discountController,
    auditLogController,
    TicketController,
    OrderController,
    paymentController,
};

Route::middleware(['auth'])
    ->prefix('super')->name('super.')->group(function () {


        // Route::get(
        //     '/',
        //     [DashboardController::class, 'index']
        // )
        //     ->name('dashboard')
        //     ->middleware('permission:dashboard.view');

        /* ===== Access Control ===== */

        // ROLES
        Route::middleware('permission:role.read')->get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::middleware('permission:role.read')->get('/roles/dt', [RoleController::class, 'datatable'])->name('roles.dt');
        Route::middleware('permission:role.create')->get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
        Route::middleware('permission:role.create')->post('/roles', [RoleController::class, 'store'])->name('roles.store');
        Route::middleware('permission:role.update')->get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
        Route::middleware('permission:role.update')->put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
        Route::middleware('permission:role.delete')->delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
        Route::middleware('permission:role.update')->get('/roles/{role}/permissions', [RoleController::class, 'editPermissions'])->name('roles.permissions.edit');
        Route::middleware('permission:role.update')->put('/roles/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.permissions.update');

        // PERMISSIONS
        Route::middleware('permission:permission.read')->get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
        Route::middleware('permission:permission.read')->get('/permissions/dt', [PermissionController::class, 'datatable'])->name('permissions.dt');
        Route::middleware('permission:permission.create')->get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
        Route::middleware('permission:permission.create')->post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
        Route::middleware('permission:permission.update')->get('/permissions/{permission}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
        Route::middleware('permission:permission.update')->put('/permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update');
        Route::middleware('permission:permission.delete')->delete('/permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');

        // USERS (manajemen user)
        Route::prefix('users')->name('user.')->group(function () {
            Route::middleware('permission:user.read')->get('/', [UserController::class, 'index'])->name('index');
            Route::middleware('permission:user.read')->get('/dt', [UserController::class, 'datatable'])->name('dt');
            Route::middleware('permission:user.create')->get('/create', [UserController::class, 'create'])->name('create');
            Route::middleware('permission:user.create')->post('/', [UserController::class, 'store'])->name('store');
            Route::middleware('permission:user.update')->get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
            Route::middleware('permission:user.update')->put('/{user}', [UserController::class, 'update'])->name('update');
            Route::middleware('permission:user.delete')->delete('/{user}', [UserController::class, 'destroy'])->name('destroy');

            Route::middleware('permission:user.update')->put('/{user}/roles', [UserManageController::class, 'syncRoles'])->name('roles.sync');
            Route::middleware('permission:user.update')->put('/{user}/perms', [UserManageController::class, 'syncPermissions'])->name('perms.sync');
        });

        /* ===== Settings ===== */








        // PRODUCTS
        Route::prefix('products')
            ->name('products.')
            ->group(function () {

                Route::middleware('permission:products.view')
                    ->get('/', [ProductController::class, 'index'])
                    ->name('index');

                Route::middleware('permission:products.view')
                    ->get('/dt', [ProductController::class, 'dt'])
                    ->name('dt');

                Route::middleware('permission:products.create')
                    ->post('/', [ProductController::class, 'store'])
                    ->name('store');

                Route::middleware('permission:products.update')
                    ->put('/{product}', [ProductController::class, 'update'])
                    ->name('update');

                Route::middleware('permission:products.delete')
                    ->delete('/{product}', [ProductController::class, 'destroy'])
                    ->name('destroy');
            });












        Route::prefix('product-prices')
            ->name('product-prices.')
            ->group(function () {

                // LIST / PAGE
                Route::middleware('permission:products.view')
                    ->get(
                        '/',
                        [ProductPriceController::class, 'index']
                    )
                    ->name('index');

                // DATATABLE
                Route::middleware('permission:products.view')
                    ->get(
                        '/dt',
                        [ProductPriceController::class, 'dt']
                    )
                    ->name('dt');

                // CREATE
                Route::middleware('permission:products.create')
                    ->post(
                        '/',
                        [ProductPriceController::class, 'store']
                    )
                    ->name('store');

                // UPDATE
                Route::middleware('permission:products.update')
                    ->put(
                        '/{productPrice}',
                        [ProductPriceController::class, 'update']
                    )
                    ->name('update');

                // DELETE
                Route::middleware('permission:products.delete')
                    ->delete(
                        '/{productPrice}',
                        [ProductPriceController::class, 'destroy']
                    )
                    ->name('destroy');
            });




        Route::prefix('holidays')
            ->name('holidays.')
            ->group(function () {

                Route::get('/', [
                    HolidayController::class,
                    'index',
                ])->name('index')
                    ->middleware('permission:holidays.view');

                Route::get('/dt', [
                    HolidayController::class,
                    'dt',
                ])->name('dt')
                    ->middleware('permission:holidays.view');

                Route::post('/', [
                    HolidayController::class,
                    'store',
                ])->name('store')
                    ->middleware('permission:holidays.create');

                Route::put('/{holiday}', [
                    HolidayController::class,
                    'update',
                ])->name('update')
                    ->middleware('permission:holidays.update');

                Route::delete('/{holiday}', [
                    HolidayController::class,
                    'destroy',
                ])->name('destroy')
                    ->middleware('permission:holidays.delete');
            });




        Route::prefix('discounts')
            ->name('discounts.')
            ->group(function () {

                Route::get('/', [
                    DiscountController::class,
                    'index'
                ])
                    ->middleware('permission:discounts.view')
                    ->name('index');

                Route::get('/dt', [
                    DiscountController::class,
                    'dt'
                ])
                    ->middleware('permission:discounts.view')
                    ->name('dt');

                Route::post('/', [
                    DiscountController::class,
                    'store'
                ])
                    ->middleware('permission:discounts.create')
                    ->name('store');

                Route::put('/{discount}', [
                    DiscountController::class,
                    'update'
                ])
                    ->middleware('permission:discounts.update')
                    ->name('update');

                Route::delete('/{discount}', [
                    DiscountController::class,
                    'destroy'
                ])
                    ->middleware('permission:discounts.delete')
                    ->name('destroy');
            });



        Route::prefix('audit-logs')
            ->name('audit-logs.')
            ->group(function () {

                Route::get('/', [
                    AuditLogController::class,
                    'index'
                ])
                    ->middleware('permission:audit-logs.view')
                    ->name('index');

                Route::get('/dt', [
                    AuditLogController::class,
                    'dt'
                ])
                    ->middleware('permission:audit-logs.view')
                    ->name('dt');

                Route::get('/{auditLog}', [
                    AuditLogController::class,
                    'show'
                ])
                    ->middleware('permission:audit-logs.view')
                    ->name('show');
            });




        Route::prefix('tickets')
            ->name('tickets.')
            ->group(function () {

                // Monitoring ticket
                Route::get('/', [TicketController::class, 'index'])
                    ->middleware('permission:tickets.view')
                    ->name('index');

                // DataTables
                Route::get('/dt', [TicketController::class, 'dt'])
                    ->middleware('permission:tickets.view')
                    ->name('dt');


                Route::get('/export', [TicketController::class, 'export'])
                    ->middleware('permission:tickets.view')
                    ->name('export');

                // Detail ticket
                Route::get('/{ticket}', [TicketController::class, 'show'])
                    ->middleware('permission:tickets.view')
                    ->name('show');

                // Cancel ticket
                Route::put('/{ticket}/cancel', [TicketController::class, 'cancel'])
                    ->middleware('permission:tickets.cancel')
                    ->name('cancel');
            });





        Route::prefix('orders')
            ->name('orders.')
            ->group(function () {

                Route::get('/', [OrderController::class, 'index'])
                    ->middleware('permission:orders.view')
                    ->name('index');

                Route::get('/dt', [OrderController::class, 'dt'])
                    ->middleware('permission:orders.view')
                    ->name('dt');

                Route::get('/export', [OrderController::class, 'export'])
                    ->middleware('permission:orders.view')
                    ->name('export');

                Route::get('/{order}', [OrderController::class, 'show'])
                    ->middleware('permission:orders.view')
                    ->name('show');
            });



        Route::prefix('payments')
            ->name('payments.')
            ->group(function () {

                // Monitoring payment
                Route::get('/', [PaymentController::class, 'index'])
                    ->middleware('permission:payments.view')
                    ->name('index');

                // DataTables
                Route::get('/dt', [PaymentController::class, 'dt'])
                    ->middleware('permission:payments.view')
                    ->name('dt');

                // Export Excel
                Route::get('/export', [PaymentController::class, 'export'])
                    ->middleware('permission:payments.view')
                    ->name('export');

                // Detail payment
                Route::get('/{payment}', [PaymentController::class, 'show'])
                    ->middleware('permission:payments.view')
                    ->name('show');
            });
    });
