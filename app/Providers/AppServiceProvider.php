<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use NativeBlade\Config\AndroidConfig;
use NativeBlade\Config\DesktopConfig;
use NativeBlade\Config\IosConfig;
use NativeBlade\Config\Permission;
use NativeBlade\Config\Plugin;
use NativeBlade\Config\PrivacyApi;
use NativeBlade\Facades\NativeBladeConfig;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        NativeBladeConfig::name('Petabit');

        // Only the optional plugins this app uses ship in the build:
        // - HAPTICS: tactile feedback on buttons (nb-feedback)
        // - BARCODE_SCANNER: reading another Petabit's QR code on the Mesclar tab
        //   (NativeBlade::scan(), uses the device camera)
        // - CLIPBOARD: copying the merge pairing code (NativeBlade::clipboardWrite())
        // - PUSH: scheduled local habit-reminder notifications (NativeBlade::notification())
        // - HTTP: native HTTP bridge to call the petabit-server API (bypasses CORS)
        // dialog/os/process/store/fs/opener are always included by core.
        NativeBladeConfig::plugins([
            Plugin::HAPTICS,
            Plugin::BARCODE_SCANNER,
            Plugin::CLIPBOARD,
            Plugin::PUSH,
            Plugin::HTTP,
        ]);

        NativeBladeConfig::desktop(function (DesktopConfig $config) {
            $config->identifier('com.petabit.app')
                ->version('1.0.0', 1)
                ->size(1200, 800)
                ->icon('src-tauri/icons/logo.png')
                ->minSize(800, 600)
                ->resizable()
                ->splashBackground('#0a0a0a');
        });

        NativeBladeConfig::android(function (AndroidConfig $config) {
            $config->identifier('com.petabit.app')
                ->version('1.0.0', 1)
                ->minSdk(28)
                ->targetSdk(34)
                ->orientation('portrait')
                ->statusBar(style: 'dark')
                ->splashBackground('#0a0a0a')
                ->permissions([
                    Permission::CAMERA => 'Ler o QR Code de outro Petabit para mesclar',
                ]);
        });

        NativeBladeConfig::ios(function (IosConfig $config) {
            $config->identifier('com.petabit.app')
                ->version('1.0.0', 1)
                ->minIosVersion('15.0')
                ->orientation('portrait')
                ->statusBar(style: 'dark')
                ->splashBackground('#0a0a0a')
                ->permissions([
                    Permission::CAMERA => 'Ler o QR Code de outro Petabit para mesclar',
                ])
                ->privacyManifest([
                    PrivacyApi::USER_DEFAULTS => PrivacyApi::USER_DEFAULTS_APP,
                    PrivacyApi::FILE_TIMESTAMP => PrivacyApi::FILE_TIMESTAMP_THIRD_PARTY,
                    PrivacyApi::SYSTEM_BOOT_TIME => PrivacyApi::BOOT_TIME_ELAPSED,
                    PrivacyApi::DISK_SPACE => PrivacyApi::DISK_SPACE_WRITE_CHECK,
                ]);
        });

        NativeBladeConfig::transition('slide');
    }
}
