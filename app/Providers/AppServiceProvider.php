<?php

namespace App\Providers;

use App\Services\AppLaunch;
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
    const VERSION = '1.2.4';
    const BUNDLE_BERSION = 1000204;

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        NativeBladeConfig::name('Petabit');

        NativeBladeConfig::onBoot(fn () => app(AppLaunch::class)->run());

        NativeBladeConfig::firebase(
            googleServices: base_path('google-services.json'),
            plist: base_path('GoogleService-Info.plist'),
        );

        NativeBladeConfig::analyticsConfig(
            autoScreenTracking: true,
            collectionEnabledByDefault: false,
        );

        NativeBladeConfig::plugins([
            Plugin::HAPTICS,
            Plugin::BARCODE_SCANNER,
            Plugin::CLIPBOARD,
            Plugin::PUSH,
            Plugin::HTTP,
            Plugin::IN_APP_REVIEW,
            Plugin::ANALYTICS,
            Plugin::NATIVE_NAV
        ]);

        NativeBladeConfig::android(function (AndroidConfig $config) {
            $config->identifier('com.petabit.app')
                ->version(self::VERSION, self::BUNDLE_BERSION)
                ->minSdk(28)
                ->targetSdk(36)
                ->orientation('portrait')
                ->statusBar(style: 'dark')
                ->splashBackground('#0a0a0a')
                ->permissions([
                    Permission::CAMERA => 'Scan the QR code of another Petabit to merge.',
                ]);
        });

        NativeBladeConfig::ios(function (IosConfig $config) {
            $config->identifier('com.petabit.app')
                ->version(self::VERSION, self::BUNDLE_BERSION)
                ->minIosVersion('15.0')
                ->orientation('portrait')
                ->statusBar(style: 'dark')
                ->splashBackground('#0a0a0a')
                ->permissions([
                    Permission::CAMERA => 'Scan the QR code of another Petabit to merge.',
                ])
                ->privacyManifest([
                    PrivacyApi::USER_DEFAULTS => PrivacyApi::USER_DEFAULTS_APP,
                    PrivacyApi::FILE_TIMESTAMP => PrivacyApi::FILE_TIMESTAMP_THIRD_PARTY,
                    PrivacyApi::SYSTEM_BOOT_TIME => PrivacyApi::BOOT_TIME_ELAPSED,
                    PrivacyApi::DISK_SPACE => PrivacyApi::DISK_SPACE_WRITE_CHECK,
                ]);
        });

        NativeBladeConfig::transition('none');
    }
}
