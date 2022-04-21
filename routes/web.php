<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Maknz\Slack\Facades\Slack;

Route::get('/', function(){    return redirect('login');});
//Route::post('/', function(){   return view('token_expired');});
Route::get('/about', function () {return view('about');});

//TODO: remove this line after testing
//Route::get('/course/{deploy_id}',              'StudentController@test')->name('test_student_view');

Route::get('/course/{deploy_id}',             function(){return view('token_expired');});
Route::post('/course/{deploy_id}',            'StudentController@index')->name('student_view');
Route::post('/course/{deploy_id}/request',    'StudentController@request')->name('student_request');
//Route::get('/course/{deploy_id}/extra_content', 'StudentController@index_content')->name('student_content');

//Route::get('/struggling',                   'StrugglingController@index')->name('struggling_view');

Auth::routes();

//Route::get('/super/retrieve_students/{delete}', 'SuperController@store')->name('store_students');
Route::get('/super/assign_remaining', 'SuperController@reassign')->name('reassign_students');
Route::get('/super/stats',            'SuperController@view_stats')->name('view_stats');
//Route::get('/super/checks',           'SuperController@check_stats')->name('check_stats');

Route::get('/force_slack', function(){

    $slack =Slack::to("#testing_tests");
    $slack->send("Slack is working in: " . App::environment());

});

Route::get('/home',                      'HomeController@index')->name('home');
Route::get('/design/new',                'DesignController@create')->name('design_new');
Route::get('/design/{id}',               'DesignController@edit')->name('design');
Route::post('/design',                   'DesignController@save')->name('design_save');
Route::post('/design/delete/{id}',       'DesignController@delete')->name('design_delete');

Route::post('/design/import',            'ImportController@import')->name('design_import');
Route::post('/design/import/courses',    'ImportController@get_courses')->name('design_import_courses');
Route::post('/design/import/resources',  'ImportController@get_resources')->name('design_import_resources');
Route::post('/design/import/save',       'ImportController@save')->name('design_import_save');

Route::post('/gamification/new',         'GamificationController@create')->name('gamification_new');
Route::get('/gamification/{id}',         'GamificationController@see')->name('gamification');
Route::post('/gamification/rename',      'GamificationController@rename')->name('gamification_rename');
Route::post('/gamification/edit/{id}',   'GamificationController@edit')->name('gamification_edit');
Route::post('/gamification/delete/{id}', 'GamificationController@delete')->name('gamification_delete');
Route::post('/gamification/deploy/imported',       'DeployController@deploy_imported')->name('deploy_imported');

Route::post('/gamification/{gam_id}/engine/new',            'GamificationController@create_engine')->name('engine_new');
Route::post('/gamification/{gam_id}/engine/edit/{id}',      'GamificationController@edit_engine')->name('engine');
Route::post('/gamification/{gam_id}/engine/delete/{id}',    'GamificationController@delete_engine')->name('engine_delete');

Route::get('/gamification/{gam_id}/deploy/configuration',   'DeployController@configure')->name('deploy');
Route::post('/gamification/{gam_id}/deploy/courses',        'DeployController@get_courses')->name('deploy_courses');
Route::post('/gamification/{gam_id}/deploy/resources',      'DeployController@get_resources')->name('deploy_resources');
Route::post('/gamification/{gam_id}/deploy/new',            'DeployController@deploy')->name('deploy_new');
Route::post('/gamification/deploy/delete/{deploy_id}',      'DeployController@delete')->name('deploy_delete');

Route::get('/engine/{engine_id}/reward/new/',               'RewardController@create')->name('reward_new');
Route::get('/engine/{engine_id}/reward/{id}',               'RewardController@edit')->name('reward');
Route::post('/engine/{engine_id}/reward/add/',              'RewardController@add')->name('reward_save');
Route::post('/engine/{engine_id}/reward/delete/{id}',       'RewardController@delete')->name('reward_delete');

Route::post('/engine/{engine_id}/condition/add/',           'ConditionController@add')->name('condition_save');
Route::get('/engine/{engine_id}/condition/new/',            'ConditionController@create')->name('condition_new');
Route::get('/engine/{engine_id}/condition/{id}',            'ConditionController@edit')->name('condition');
Route::post('/engine/{engine_id}/condition/delete/{id}',    'ConditionController@delete')->name('condition_delete');

Route::get('/logout', function(){ Auth::logout(); return view('auth.login');});