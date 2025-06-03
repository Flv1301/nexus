<div class="form-row">
    <div class="form-group col-md-4">
        <x-adminlte-input
            name="social"
            id="social"
            label="Endereço/Perfil"
            type="url"
            placeholder="Endereço ou perfil"
            value="{{ old('social') ?? $social->social ?? ''}}">
            <x-slot name="prependSlot">
                <div class="input-group-text text-black">
                    <i class="fas fa-comment"></i>
                </div>
            </x-slot>
        </x-adminlte-input>
    </div>
    <div class="form-group col-md-4">
        <x-adminlte-select
            name="type"
            id="type"
            label="Rede Social">
            <option/>
            <option value="amazon">Amazon</option>
            <option value="behance">Behance</option>
            <option value="blogger">Blogger</option>
            <option value="deviantart">DeviantArt</option>
            <option value="discord">Discord</option>
            <option value="dribbble">Dribbble</option>
            <option value="etsy">Etsy</option>
            <option value="facebook">Facebook</option>
            <option value="flickr">Flickr</option>
            <option value="github">GitHub</option>
            <option value="goodreads">Goodreads</option>
            <option value="googleplus">Google+</option>
            <option value="imdb">IMDb</option>
            <option value="instagram">Instagram</option>
            <option value="linkedin">LinkedIn</option>
            <option value="line">LINE</option>
            <option value="medium">Medium</option>
            <option value="olifans">Olifans</option>
            <option value="patreon">Patreon</option>
            <option value="pinterest">Pinterest</option>
            <option value="quora">Quora</option>
            <option value="reddit">Reddit</option>
            <option value="skype">Skype</option>
            <option value="slack">Slack</option>
            <option value="snapchat">Snapchat</option>
            <option value="soundcloud">SoundCloud</option>
            <option value="spotify">Spotify</option>
            <option value="stackoverflow">Stack Overflow</option>
            <option value="telegram">Telegram</option>
            <option value="tiktok">TikTok</option>
            <option value="tinder">Tinder</option>
            <option value="tumblr">Tumblr</option>
            <option value="twitch">Twitch</option>
            <option value="twitter">Twitter</option>
            <option value="viber">Viber</option>
            <option value="vimeo">Vimeo</option>
            <option value="wechat">WeChat</option>
            <option value="whatsapp">WhatsApp</option>
            <option value="wordpress">WordPress</option>
            <option value="xing">Xing</option>
            <option value="youtube">YouTube</option>
        </x-adminlte-select>
    </div>
    <div class="form-group col-md-4">
        <x-adminlte-input
            name="social_id"
            id="social_id"
            label="ID"
            type="text"
            placeholder="ID do perfil/usuário"
            value="{{ old('social_id') ?? $social->social_id ?? ''}}">
            <x-slot name="prependSlot">
                <div class="input-group-text text-black">
                    <i class="fas fa-id-badge"></i>
                </div>
            </x-slot>
        </x-adminlte-input>
    </div>
</div>

@push('css')
<style>
/* Regra CSS específica e forte para garantir que campo social não tenha uppercase */
input[name="social"], 
#social,
input[name="social"].form-control,
#social.form-control {
    text-transform: none !important;
}
</style>
@endpush
