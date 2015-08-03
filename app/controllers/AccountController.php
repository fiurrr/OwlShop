<?php
use Carbon\Carbon;

class AccountController extends \BaseController {
	
	//Sign in function start
	public function postSignIn(){
		$validator = Validator::make(Input::all(),
			array(
				'email_sign' 		=> 'required|email',
				'password' 			=> 'required',
			)
		);
		if($validator->fails()){
			return Redirect::route('account-create')
				->with('global', 'Przepraszamy ale wystąpił problem z logowaniem. Proszę spróbować jeszcze raz')
				->withInput();	
		}else{
			if($auth = Auth::attempt(array(
				'email' => Input::get('email_sign'),
				'password' => Input::get('password')
			))){
				if(Auth::user()->permission == 1){
					return Redirect::to('/administrator');
				}else{
					return Redirect::to('/moj_prestiz');
				}
			}else{
				return Redirect::route('account-create')
					->with('global', 'Email/haslo jest nie prawidlowe');
			}
		}
		return Redirect::route('account-create')
			->with('global', 'Przepraszamy ale wystąpił problem z logowanie. Proszę spróbować jeszcze raz');
	}
	//Sign in function end
	
	//Sign out function start
	public function getSignOut(){
		Auth::logout();
		return Redirect::route('home_page');
	}
	//Sign out function end
	
	//Create new account function start
	public function getCreate(){
		$cities = City::all();
		return View::make('account.create')
			->with('title', 'Prestiż.pl | Pozwolimy ci ulec pokusie. Najseksowniejsze i najbardziej zmysłowe dziewczyny w Polsce')
			->with('cities', $cities);
	}
	public function postCreate(){
		$settings = Settings::where('id', '=', '1')->first();
		$validator = Validator::make(Input::all(),
			array(
				'email' 				=> 'required|max:50|email|unique:users',
				'password' 			=> 'required|min:6',
				'password_again' 	=> 'required|same:password'
			)
		);
		if($validator->fails()){
			return Redirect::route('account-create')
				->with('global', 'Przepraszamy ale wystąpił problem z otworzeniem konta. Proszę spróbować jeszcze raz')
				->withInput();
		}else{
			$email 		= Input::get('email');
			$password 	= Input::get('password');
			//Activation code
			$code		= str_random(60);
			$user = User::create(array(
				'email' 		=> $email,
				'password' 	=> Hash::make($password),
				'code' 		=> $code,
				'active' 	=> 0
			));
			if($user){
				$newDetails = new Details;
				$newDetails->user_id = $user->id;
				$newDetails->status = 1;
				$newDetails->save();
				//Send link function start
				Mail::send('emails.auth.active', array(
					'link' => URL::route('account-active', $code),
					'email' => $email),function($message) use ($user){$message->to($user->email, $user->email)->subject('Weryfikacja twojego konta do Prestiż.pl');});
				if($settings->value == 1){
					Mail::send('emails.auth.payment', array('email' => $email, 'id'=>$user->id),function($message) use ($user){$message->to($user->email, $user->email)->subject('Oplata za ogloszenie na Prestiż.pl');});
				}
				//Send link function end
				return Redirect::route('account-create')
					->with('global', 'Gratulujemy twoje konto zostało stworzone! Wysłalismy do ciebie email z linkiem weryfikacyjnym. Możesz teraz zalogować się do swojego konta');
			}
		}
	}
	public function getActivate($code){
		$user = User::where('code', '=', $code)->where('active', '=', 0);
		if($user->count()){
			$user = $user->first();
			//Update user to active state
			$user->active 	= 1;
			$user->code 		='';
			
			if($user->save()){
				return Redirect::route('home')
					->with('global', 'Twoje konto zostało pozytywnie zweryfikowane. Proszę teraz zalogowac się do swojego konta');
			}
		}
			return Redirect::route('home')
				->with('global', 'Nie udalo się zweryfikować twojego profilu. Proszę spróbować jeszcze raz');
	}
	//Create new account function end

    public function showUserProfiles()
    {
        $product = Products::all();

        View::make('index');
    }
    public function showProfile($id)
    {
        $details = Details::where('id', '=', $id)->first();
        $gallery = Gallery::where('user_id', '=', $id)->get();
        $profilePhoto = Profile::where('user_id', '=', $id)->first();
        $userC = User::where('id', Auth::user()->id)->first();
        $cities = City::all();
        return View::make('account.profile')
            ->with('title', 'Prestiż.pl | Pozwolimy ci ulec pokusie. Najseksowniejsze i najbardziej zmysłowe dziewczyny w Polsce')
            ->with('cities', $cities)
            ->with('details', $details)
            ->with('profilePhoto', $profilePhoto)
            ->with('gallery', $gallery)
            ->with('userC', $userC);

    }
    public function openAddProfile(){
        if(Auth::check()) {
            $cities = City::all();
            $userC = User::where('id', '=', Auth::user()->id)->first();
            return View::make('account.add')
                ->with('title', 'Prestiż.pl | Pozwolimy ci ulec pokusie. Najseksowniejsze i najbardziej zmysłowe dziewczyny w Polsce')
                ->with('cities', $cities)
                ->with('userC', $userC);
        }
    }
    public function addProfile(){
        $detail = new Details;
        $detail->user_id = Auth::user()->id;
        $detail->status = 1;

        $detail->nick = Input::get('nick');
        $detail->phone = Input::get('phone');
        $detail->city = Input::get('city');
        $detail->age = Input::get('age');
        $detail->weight = Input::get('weight');
        $detail->height = Input::get('height');
        $detail->size = Input::get('size');
        $detail->eyes = Input::get('eyes');
        $detail->hair = Input::get('hair');
        $detail->body = Input::get('body');
        $detail->languages = Input::get('languages');
        $detail->des = Input::get('des');
        $detail->offer = Input::get('offer');
        $detail->trip = Input::get('trip');
        $detail->halfHour = Input::get('half');
        $detail->hour = Input::get('hour');
        $detail->twoHours = Input::get('twoHours');
        $detail->night = Input::get('night');

        (Input::get('blur')==1) ? $detail->blur=1 : $detail->blur=0;


        if($detail->save()){
            return Redirect::to('moj_prestiz')->with('global', 'Utworzono nowy profil');
        } else {
            return Redirect::back()->with('global', 'Niestety nie udało się utworzyć nowego profilu. Proszę spróbować jeszcze raz');
        }
    }
    public function openProfilePayments($id){
        $id = (int)$id;
        $cities = City::all();
        $basicPayment = BasicPayment::where('user_id', '=', $id)->get();
        $premiumPayment = PremiumPayment::where('user_id', '=', $id)->get();
        $userC = User::where('id', Auth::user()->id)->first();
        $settings = Settings::where('id', '=', '1')->first();
        return View::make('account.payments')
            ->with('title', 'Prestiż.pl | Pozwolimy ci ulec pokusie. Najseksowniejsze i najbardziej zmysłowe dziewczyny w Polsce')
            ->with('cities', $cities)
            ->with('basicPayment', $basicPayment)
            ->with('premiumPayment', $premiumPayment)
            ->with('userC', $userC)
            ->with('id', $id)
            ->with('settings', $settings);
    }
    public function openProfileWoman($id){
        $gallery = Gallery::where('user_id', '=', $id)->get();
        $profilePhoto = Profile::where('user_id', '=', $id)->first();
        $details = Details::where('id', '=', $id)->first();
        $userC = User::where('id', $details->user_id)->first();
        $cities = City::all();
        return View::make('account.profile')
            ->with('title', 'Prestiż.pl | Pozwolimy ci ulec pokusie. Najseksowniejsze i najbardziej zmysłowe dziewczyny w Polsce')
            ->with('cities', $cities)
            ->with('details', $details)
            ->with('profilePhoto', $profilePhoto)
            ->with('gallery', $gallery)
            ->with('userC', $userC);
    }
	public function openProfileEdit($id){
		$details = Details::where('id', '=', $id)->first();
		$cities = City::all();
		$userC = User::where('id', Auth::user()->id)->first();
		return View::make('account.edit')
			->with('title', 'Prestiż.pl | Pozwolimy ci ulec pokusie. Najseksowniejsze i najbardziej zmysłowe dziewczyny w Polsce')
			->with('cities', $cities)
			->with('details', $details)
			->with('userC', $userC);
	}
    public function chooseUserType($id)
    {
        $user = User::where('id', '=', Auth::user()->id)->first();

        ((int)$id == 1) ? $id = 1 : $id = 2;

        $user->type = $id;

        if ($id == 1) {

        }

        if($user->save())
        {
            return Redirect::back()->with('global', 'Wybrałeś typ swojego konta, zmiany zostały zapisane.');
        } else {
            return Redirect::back()->with('global', 'Przepraszamy ale nie udało się zapisać twojego profilu. Proszę spróbować jeszcze raz');
        }

    }
    public function removeProfile($id){
        $id = (int)$id;
        $detail = Details::where('id', '=', $id)->first();

        if(Auth::check() && Auth::user()->id == $detail->user_id)
        {
            $profilePhoto = Profile::where('user_id', '=', $id)->first();
            $gallery = Gallery::where('user_id', '=', $id)->get();

            foreach ($gallery as $item) {
                File::delete('public/gallery/'.$item->gallery_track);
                $item->delete();
            }

            if(!empty($profilePhoto)) {
                File::delete('public/profile/' . $profilePhoto->profile_track);
                $profilePhoto->delete();
            }
            $detail->delete();

            return Redirect::to('moj_prestiz')
                    ->with('global', 'Pomyślnie usunięto profil');
        } else return Redirect::to('moj_prestiz')->with('global', 'Brak uprawnień');

    }

	public function saveProfile($id){
        $id = (int)$id;
		$details = Details::where('id', '=', $id)->first();

        if($details->user_id == Auth::user()->id) {
            $details->nick = Input::get('nick');
            $details->phone = Input::get('phone');
            $details->city = Input::get('city');
            $details->age = Input::get('age');
            $details->weight = Input::get('weight');
            $details->height = Input::get('height');
            $details->size = Input::get('size');
            $details->eyes = Input::get('eyes');
            $details->hair = Input::get('hair');
            $details->body = Input::get('body');
            $details->languages = Input::get('languages');
            $details->des = Input::get('des');
            $details->offer = Input::get('offer');
            $details->trip = Input::get('trip');
            $details->halfHour = Input::get('half');
            $details->hour = Input::get('hour');
            $details->twoHours = Input::get('twoHours');
            $details->night = Input::get('night');
            if ($details->blur == 0) {
                $details->blur = Input::get('blur');
            }
            if ($details->save()) {
                return Redirect::back()->with('global', 'Twój profil został zapisany');
            } else {
                return Redirect::back()->with('global', 'Przepraszamy ale nie udało się zapisać twojego profilu. Proszę spróbować jeszcze raz');
            }
        } else {
            return Redirect::back()->with('global', 'Odejdź precz zły hakierze !');
        }
	}
	public function saveProfilePhoto(){
        $id = (int)Input::get('user_id');
		$checkerP = Profile::where('user_id', '=', $id)->first();
		if(count($checkerP)){
			File::delete('public/profile/'.$checkerP->profile_track);
			$file            = Input::file('main_photo_profile');
			$destinationPath = 'public/profile/';
			$filename        = str_random(42) . $file->getClientOriginalName();
			$uploadSuccess   = $file->move($destinationPath, $filename);
			$checkerP->profile_track = $filename;
			
			if($checkerP->save()){
				return Redirect::back();
			}else{
				return Redirect::back()
					->with('global', 'Nie udało się zapisać twojego zdjęcia. Proszę spróbować jeszcze raz');
			}
		}else{
			$profile =  new Profile;
			$profile->user_id = $id;
			$file            = Input::file('main_photo_profile');
			$destinationPath = 'public/profile/';
			$filename        = str_random(42) . $file->getClientOriginalName();
			$uploadSuccess   = $file->move($destinationPath, $filename);
			$profile->profile_track = $filename;
			
			if($profile->save()){
				return Redirect::back();
			}else{
				return Redirect::back()
					->with('global', 'Nie udało się zapisać twojego zdjęcia. Proszę spróbować jeszcze raz');
			}
		}
	}
	public function saveGalleryPhoto(){
		$id = (int)Input::get('user_id');
        $gallery =  new Gallery;
		$gallery->user_id 	= $id;
		$file            	= Input::file('photo_add_gallery');
		$destinationPath 	= 'public/gallery/';
		$filename        	= str_random(42) . $file->getClientOriginalName();
		$uploadSuccess   	= $file->move($destinationPath, $filename);
		$gallery->gallery_track = $filename;
		
		if($gallery->save()){
			return Redirect::back();
		}else{
			return Redirect::back()
				->with('global', 'Nie udało się zapisać twojego zdjęcia. Proszę spróbować jeszcze raz');
		}
	}
	public function removeGalleryPhoto($id){
		$itemGallery = Gallery::where('id', '=', $id)->first();
		$itemGallery->delete();
		File::delete('public/gallery/'.$itemGallery->gallery_track);
		return Redirect::back();
	}
	public function adminAccess(){
		if(Auth::user()->permission == 1){
			$userDetailsC = User::where('permission', '=', '0')->count();
			$profiles = Details::all();
			return View::make('admin.admin')
				->with('title', 'Admin | Prestiż.pl | Pozwolimy ci ulec pokusie. Najseksowniejsze i najbardziej zmysłowe dziewczyny w Polsce')
				->with('profiles', $profiles)
				->with('userDetailsC', $userDetailsC);
		}else{
			return Redirect::to('/moj_prestiz');
		}
	}
	public function adminAccessUser($id){
		if(Auth::user()->permission == 1){
			$gallery = Gallery::where('user_id', '=', $id)->get();
			$profilePhoto = Profile::where('user_id', '=', $id)->first();
			$user = Details::where('id', '=', $id)->first();
			$basicPayment = BasicPayment::where('user_id', '=', $id)->get();
			$premiumPayment = PremiumPayment::where('user_id', '=', $id)->get();
			return View::make('admin.userDetails')
				->with('title', 'Admin | Prestiż.pl | Pozwolimy ci ulec pokusie. Najseksowniejsze i najbardziej zmysłowe dziewczyny w Polsce')
				->with('user', $user)
				->with('basicPayment', $basicPayment)
				->with('premiumPayment', $premiumPayment)
				->with('profilePhoto', $profilePhoto)
				->with('gallery', $gallery);
		}else{
			return Redirect::to('/moj_prestiz');
		}
	}
	public function adminAccessUserDeactive($id){
		$details = Details::where('id', '=', $id)->first();
		$details->status = 1;
		$details->save();
		return Redirect::back();
	}
	public function adminAccessUserActive($id){
		$details = Details::where('id', '=', $id)->first();
		$details->status = 2;
		$details->save();
		return Redirect::back();
	}
	public function adminAccessUserProfile($id){
		$gallery = Gallery::where('user_id', '=', $id)->get();
		$profilePhoto = Profile::where('user_id', '=', $id)->first();
		$details = Details::where('id', '=', $id)->first();
		$userC = User::where('id', $details->user_id)->first();
		$cities = City::all();
		return View::make('account.profile')
			->with('title', 'Prestiż.pl | Pozwolimy ci ulec pokusie. Najseksowniejsze i najbardziej zmysłowe dziewczyny w Polsce')
			->with('cities', $cities)
			->with('details', $details)
			->with('profilePhoto', $profilePhoto)
			->with('gallery', $gallery)
			->with('userC', $userC);
	}
	public function adminAccessSettings(){
		$offerSettings = Settings::where('id', '=', '1')->first();
		return View::make('admin.adminSettings')
				->with('title', 'Admin | Prestiż.pl | Pozwolimy ci ulec pokusie. Najseksowniejsze i najbardziej zmysłowe dziewczyny w Polsce')
				->with('offerSettings', $offerSettings);
	}
	public function adminAccessSettingsTerms(){
		$terms = Terms::where('id', '=', '1')->first();
		return View::make('admin.adminSettingsTerms')
				->with('title', 'Admin | Prestiż.pl | Pozwolimy ci ulec pokusie. Najseksowniejsze i najbardziej zmysłowe dziewczyny w Polsce')
				->with('terms', $terms);
	}
	public function adminAccessSettingsTermsSave(){
		$terms = Terms::where('id', '=', '1')->first();
		$terms->terms = Input::get('terms_edit_admin');
		$terms->save();
		return Redirect::back();
	}
    public function adminAccessSettingsFaq(){
        $terms = Terms::where('id', '=', '2')->first();
        return View::make('admin.adminSettingsFaq')
                ->with('title', 'Admin | Prestiż.pl | Pozwolimy ci ulec pokusie. Najseksowniejsze i najbardziej zmysłowe dziewczyny w Polsce')
                ->with('terms', $terms);
    }
    public function adminAccessSettingsFaqSave(){
        $terms = Terms::where('id', '=', '2')->first();
        $terms->terms = Input::get('faq_edit_admin');
        $terms->save();
        return Redirect::back();
    }
	public function adminAccessSettingsCookie(){
		$terms = Terms::where('id', '=', '3')->first();
		return View::make('admin.adminSettingsCookie')
				->with('title', 'Admin | Prestiż.pl | Pozwolimy ci ulec pokusie. Najseksowniejsze i najbardziej zmysłowe dziewczyny w Polsce')
				->with('terms', $terms);
	}
	public function adminAccessSettingsCookieSave(){
		$terms = Terms::where('id', '=', '3')->first();
		$terms->terms = Input::get('cookie_edit_admin');
		$terms->save();
		return Redirect::back();
	}

	public function adminAccessSettingsON(){
		$offerSettings = Settings::where('id', '=', '1')->first();
		$offerSettings->value = 2;
		$offerSettings->save();
		return Redirect::back();
	}
	public function adminAccessSettingsOFF(){
		$offerSettings = Settings::where('id', '=', '1')->first();
		$offerSettings->value = 1;
		$offerSettings->save();
		return Redirect::back();
	}
	public function adminAddBasicPayment(){
		$user_id = Input::get('user_id_basic');
		$time_active = Input::get('basic_payment_time');
		$details = Details::where('id', '=', $user_id)->first();
		$today = Carbon::now();
		$basicPayment = new BasicPayment;
		$basicPayment->user_id = $user_id;
		$basicPayment->finish_at = Carbon::now()->addDays($time_active);
		if($basicPayment->save()){
			$details->payment = $basicPayment->finish_at;
			$details->save();
			return Redirect::back();
		}
	}
	public function adminAddPremiumPayment(){
		$user_id = Input::get('user_id_premium');
		$time_active = Input::get('premium_payment_time');
		$details = Details::where('id', '=', $user_id)->first();
		$today = Carbon::now();
		$premiumPayment = new PremiumPayment;
		$premiumPayment->user_id = $user_id;
		$premiumPayment->finish_at = Carbon::now()->addDays($time_active);
		if($premiumPayment->save()){
			$details->premium = $premiumPayment->finish_at;
			$details->save();
			return Redirect::back();
		}
	}
	public function adminDownloadImage($id){
		$profilePhoto = Profile::where('id', '=', $id)->first();
		$file= "public/profile/".$profilePhoto->profile_track;
		return Response::download($file);
	}
	public function adminDownloadImageG($id){
		$galleryPhoto = Gallery::where('id', '=', $id)->first();
		$file= "public/gallery/".$galleryPhoto->gallery_track;
		return Response::download($file);
	}
	public function adminUploadProfile(){
		$id = Input::get('id_profile_photo');
		$profilePhoto = Profile::where('id', '=', $id)->first();
		File::delete('public/profile/'.$profilePhoto->profile_track);
		$file            = Input::file('file_profile_photo');
		$destinationPath = 'public/profile/';
		$filename        = str_random(42) . $file->getClientOriginalName();
		$uploadSuccess   = $file->move($destinationPath, $filename);
		$profilePhoto->profile_track = $filename;
		if($profilePhoto->save()){
			return Redirect::back()
				->with('global', 'Edycja zakończona sukcesem');
				
		}
	}
	public function adminUploadGallery(){
		$id = Input::get('id_gallery_photo');
		$galleryPhoto = Gallery::where('id', '=', $id)->first();
		File::delete('public/gallery/'.$galleryPhoto->gallery_track);
		$file            = Input::file('file_gallery_photo');
		$destinationPath = 'public/gallery/';
		$filename        = str_random(42) . $file->getClientOriginalName();
		$uploadSuccess   = $file->move($destinationPath, $filename);
		$galleryPhoto->gallery_track = $filename;
		if($galleryPhoto->save()){
			return Redirect::back()
				->with('global', 'Edycja zakończona sukcesem');
				
		}
	}
}
?>