<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class AuthResources
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$permission_name)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        //first check that name in your db
        $permission = Permission::where('name',$permission_name)->first();
            if($permission){
                //here you have to get logged in user role
                $role_id = Auth::user()->roles->first()->id;

                ## so now check permission
                $check_permission = DB::table('role_has_permissions')->where('role_id',$role_id)->where('permission_id',$permission->id)->first();

                if(isset($check_permission)){
                    return $next($request);
                }else{
                    abort('401');
                }
                //if Permission not assigned for this user role show what you need
            }
            // if Permission name not in table then do what you need
            ## Ex1 : return 'Permission not in Database';
            ## Ex2 : return redirect()->back();

        }

}
