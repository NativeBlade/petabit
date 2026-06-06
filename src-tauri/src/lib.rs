#[cfg_attr(mobile, tauri::mobile_entry_point)]
pub fn run() {
    let builder = nativeblade::build();

    // nativeblade:plugins:start

    #[cfg(feature = "haptics")]
    let builder = builder.plugin(tauri_plugin_haptics::init());

    #[cfg(all(any(target_os = "android", target_os = "ios"), feature = "barcode_scanner"))]
    let builder = builder.plugin(tauri_plugin_barcode_scanner::init());

    #[cfg(feature = "clipboard")]
    let builder = builder.plugin(tauri_plugin_clipboard_manager::init());

    #[cfg(all(any(target_os = "android", target_os = "ios"), feature = "push"))]
    let builder = builder.plugin(tauri_plugin_nativeblade_push::init());

    #[cfg(feature = "http")]
    let builder = builder.plugin(tauri_plugin_http::init());

    #[cfg(all(any(target_os = "android", target_os = "ios"), feature = "in_app_review"))]
    let builder = builder.plugin(tauri_plugin_nativeblade_review::init());
    // nativeblade:plugins:end

    builder
        .run(tauri::generate_context!())
        .expect("error while running NativeBlade");
}
