<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $s = Setting::allKeyed();
        return view('admin.settings.index', compact('s'));
    }

    public function update(Request $request)
    {
        $allowed = [
            'store_name','store_email','store_phone',
            'wa_number','wa_name',
            'topbar_text',
            'hero_title_1','hero_title_2','hero_title_3','hero_desc',
            'hero_video','hero_video_opts',
            'frete_fixo','frete_gratis_acima','frete_prazo_min','frete_prazo_max',
            'social_instagram','social_facebook','social_tiktok','social_shopee',
            'product_sizes_global',
        ];

        $data = array_intersect_key($request->all(), array_flip($allowed));
        Setting::setMany($data);

        // tab → hash para redirecionar de volta à aba certa
        $tabHash = [
            'general'    => 'general',
            'shipping'   => 'shipping',
            'sizes'      => 'sizes',
            'appearance' => 'appearance',
            'video'      => 'appearance',
            'whatsapp'   => 'whatsapp',
            'social'     => 'social',
        ];
        $hash = $tabHash[$request->input('tab', 'general')] ?? 'general';

        return redirect(route('admin.settings.index') . '#' . $hash)->with('success', 'Configurações salvas!');
    }

    public function updateStripe(Request $request)
    {
        $request->validate([
            'stripe_pk'      => 'nullable|string',
            'stripe_sk'      => 'nullable|string',
            'stripe_webhook' => 'nullable|string',
        ]);

        $data = ['stripe_pk' => $request->stripe_pk ?? ''];
        if ($request->filled('stripe_sk')) {
            $data['stripe_sk'] = $request->stripe_sk;
        }
        if ($request->has('stripe_webhook')) {
            $data['stripe_webhook'] = $request->stripe_webhook ?? '';
        }

        Setting::setMany($data);

        return redirect(route('admin.settings.index') . '#stripe')->with('success', 'Chaves Stripe salvas!');
    }

    public function uploadHeroImage(Request $request)
    {
        // Upload de arquivo
        if ($request->hasFile('hero_image')) {
            $request->validate(['hero_image' => 'image|max:5120']);
            $path = $request->file('hero_image')->store('uploads', 'public');
            $url  = asset('storage/' . $path);
            Setting::set('hero_image', $url);
            Setting::set('hero_video', '');
            return redirect(route('admin.settings.index') . '#appearance')->with('success', 'Foto do hero atualizada!');
        }

        // URL direta
        if ($request->filled('hero_image_url')) {
            $request->validate(['hero_image_url' => 'url']);
            Setting::set('hero_image', $request->hero_image_url);
            Setting::set('hero_video', '');
            return redirect(route('admin.settings.index') . '#appearance')->with('success', 'Foto do hero atualizada!');
        }

        return redirect(route('admin.settings.index') . '#appearance')->with('success', 'Nenhuma imagem enviada.');
    }
}
