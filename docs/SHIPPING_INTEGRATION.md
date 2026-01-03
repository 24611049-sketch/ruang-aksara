Shipping Integration (Ruang Aksara)

Overview
--------
The application includes a simple shipping cost estimator implemented in `App\Services\ShippingCalculator`.
By default it uses a flat fallback rate model (base + per-kg). You can enable external carrier API integration to fetch live shipping costs by setting environment variables.

Environment
-----------
Add these variables to your `.env` (or copy from `.env.example`):

- SHIPPING_PROVIDER=flat|auto|jne|jnt|ninja|antera

Provider-specific (example):
- JNE_API_URL=https://api.jne.example/cost
- JNE_API_KEY=your_jne_api_key_here

- JNT_API_URL=https://api.jnt.example/cost
- JNT_API_KEY=your_jnt_api_key_here

- NINJA_API_URL=https://api.ninja.example/cost
- NINJA_API_KEY=your_ninja_api_key_here

- ANTERA_API_URL=https://api.antera.example/cost
- ANTERA_API_KEY=your_antera_api_key_here

Behavior
--------
- If `SHIPPING_PROVIDER` is `flat` (default), the `ShippingCalculator` uses local flat-rate tables.
- If `SHIPPING_PROVIDER` is `auto`, the calculator will try to call the provider API matching the selected shipping method.
- If `SHIPPING_PROVIDER` is set to a specific provider key (e.g. `jne`), the calculator will attempt to call that provider's API for all requests.

API Contract (recommended)
--------------------------
Carrier APIs may differ. The calculator expects a JSON response containing either:

1) { "cost": 12345 }

or

2) { "results": [{ "cost": 12345 }] }

If the response doesn't match these shapes, the calculator will fallback to the flat-rate estimator.

Notes
-----
- The current integration uses Laravel's HTTP client (Http facade). Make sure outbound HTTP is allowed in your environment.
- For production, store API keys securely and restrict access.
- You may extend `App\Services\ShippingCalculator` to implement provider-specific request/response logic if carriers return different payloads.

Next steps
----------
If you want, I can:
- Implement provider-specific adapters for JNE/JNT/Ninja/AnterAja to support official API payloads.
- Add ETA estimates to the UI.
- Add per-book weight column to `books` table and migrate existing data.
