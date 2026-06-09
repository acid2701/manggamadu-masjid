<?php

use App\Models\Setting;
use App\Services\SettingService;

it('can get a setting value', function () {
    Setting::create([
        'group' => 'masjid',
        'key' => 'test_key',
        'value' => 'test_value',
        'type' => 'text',
        'label' => 'Test',
    ]);

    $service = app(SettingService::class);

    expect($service->get('test_key'))->toBe('test_value');
});

it('returns default when key not found', function () {
    $service = app(SettingService::class);

    expect($service->get('nonexistent', 'fallback'))->toBe('fallback');
});

it('can set a setting value', function () {
    Setting::create([
        'group' => 'masjid',
        'key' => 'update_test',
        'value' => 'old_value',
        'type' => 'text',
        'label' => 'Test',
    ]);

    $service = app(SettingService::class);
    $service->set('update_test', 'new_value');

    expect($service->get('update_test'))->toBe('new_value');
});

it('can get settings by group', function () {
    Setting::create(['group' => 'test_group', 'key' => 'key1', 'value' => 'val1', 'type' => 'text', 'label' => 'K1']);
    Setting::create(['group' => 'test_group', 'key' => 'key2', 'value' => 'val2', 'type' => 'text', 'label' => 'K2']);
    Setting::create(['group' => 'other', 'key' => 'key3', 'value' => 'val3', 'type' => 'text', 'label' => 'K3']);

    $service = app(SettingService::class);
    $groupSettings = $service->getByGroup('test_group');

    expect($groupSettings)->toHaveCount(2);
});

it('correctly checks boolean settings', function () {
    Setting::create(['group' => 'display', 'key' => 'enabled_setting', 'value' => 'true', 'type' => 'boolean', 'label' => 'Enabled']);
    Setting::create(['group' => 'display', 'key' => 'disabled_setting', 'value' => 'false', 'type' => 'boolean', 'label' => 'Disabled']);

    $service = app(SettingService::class);

    expect($service->isEnabled('enabled_setting'))->toBeTrue()
        ->and($service->isEnabled('disabled_setting'))->toBeFalse()
        ->and($service->isEnabled('nonexistent'))->toBeFalse();
});
