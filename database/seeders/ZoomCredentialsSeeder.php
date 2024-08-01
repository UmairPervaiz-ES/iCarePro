<?php

namespace Database\Seeders;

use App\Models\ZoomCredentials;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ZoomCredentialsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ZoomCredentials::truncate();

        $env = env('APP_ENV');
        if ($env == 'local') {
            ZoomCredentials::create([
                'access_token' => 'eyJhbGciOiJIUzUxMiIsInYiOiIyLjAiLCJraWQiOiJmZDEzZmY0Ny00ZTcyLTQzZTgtYTMzMC05YjhiNTUwNDg4ZGEifQ.eyJ2ZXIiOjcsImF1aWQiOiIyNmJhZTIzZWM5OGFlODQ2ODM1NmMzZjc2MjFjMmNjNyIsImNvZGUiOiJRSXl5NHpGU2ZhdHpUWVd2VGNqUS15cVgtVHpHMmJFSkEiLCJpc3MiOiJ6bTpjaWQ6SEV4bmFKeVdRRk9OTE9GdVdScHBnIiwiZ25vIjowLCJ0eXBlIjowLCJ0aWQiOjksImF1ZCI6Imh0dHBzOi8vb2F1dGguem9vbS51cyIsInVpZCI6Imd5Z25XZWZ6UlUyM2N4ZElNR29RWFEiLCJuYmYiOjE2NzExOTQ4NTEsImV4cCI6MTY3MTE5ODQ1MSwiaWF0IjoxNjcxMTk0ODUxLCJhaWQiOiJlSjh0ekZCS1E4aUdYWG5SYk9FVEFnIiwianRpIjoiMGQwNzc0YTUtYjkyZS00ZmU3LTljYWQtMjViNmU0ZjhlYzVlIn0',
                'refresh_token' => 'eyJhbGciOiJIUzUxMiIsInYiOiIyLjAiLCJraWQiOiI3OGViZGEyMS04MzQ2LTRiYTQtOWFlMi0zMDYxZDU1MzQ2OGMifQ.eyJ2ZXIiOjcsImF1aWQiOiIyNmJhZTIzZWM5OGFlODQ2ODM1NmMzZjc2MjFjMmNjNyIsImNvZGUiOiJRSXl5NHpGU2ZhdHpUWVd2VGNqUS15cVgtVHpHMmJFSkEiLCJpc3MiOiJ6bTpjaWQ6SEV4bmFKeVdRRk9OTE9GdVdScHBnIiwiZ25vIjowLCJ0eXBlIjoxLCJ0aWQiOjExLCJhdWQiOiJodHRwczovL29hdXRoLnpvb20udXMiLCJ1aWQiOiJneWduV2VmelJVMjNjeGRJTUdvUVhRIiwibmJmIjoxNjcxNDMzODI1LCJleHAiOjIxNDQ0NzM4MjUsImlhdCI6MTY3MTQzMzgyNSwiYWlkIjoiZUo4dHpGQktROGlHWFhuUmJPRVRBZyIsImp0aSI6IjY5ZDM4Y2M5LTA0ODItNDUyZi04ZWU4LTMxMWYwNGVmYTJhNyJ9.8znRJNzzzvIb_xNfRT8u0fP8lTX2S4fSH8LMEPS75QRUZJWL5HaSPYO1eH-UFuDs3Fryd8GWNSjdtoV-zHKBZg',
                'token_updated_at' => '2022-12-19 07:10:25',
                'client_id' => 'HExnaJyWQFONLOFuWRppg',
                'client_secret' => 'cq1Mv2E07WNjVuzSUOVkcU8YvyLQ0UJf'
            ], 200);
        }elseif ($env == 'dev') {
            ZoomCredentials::create([
                'access_token' => 'eyJhbGciOiJIUzUxMiIsInYiOiIyLjAiLCJraWQiOiI3ZTc5MTdhZS1jYzllLTQ4YjMtOTlhNy02ZWRjN2ZiOGMwZmEifQ.eyJ2ZXIiOjcsImF1aWQiOiI4MTUwNjQwMzNmODM5OTNkOWRmM2U0YTM0ODk2MGY4MiIsImNvZGUiOiJETmhzcG5jTERwQ2ZxY3pqS1dJU1VTLXZfTDlqZ3VYV0EiLCJpc3MiOiJ6bTpjaWQ6NU1kNkRrOWdRNnVGUHZIR3BlX2hPUSIsImdubyI6MCwidHlwZSI6MCwidGlkIjowLCJhdWQiOiJodHRwczovL29hdXRoLnpvb20udXMiLCJ1aWQiOiJtU2dxXzJjWVRuYWY5bUhkUkY2SkN3IiwibmJmIjoxNjcxNDQwNjE5LCJleHAiOjE2NzE0NDQyMTksImlhdCI6MTY3MTQ0MDYxOSwiYWlkIjoia3RVZUN6ZFVSZXFOWElxMmE4MVQ5QSIsImp0aSI6IjVmNDA1NWE2LWYyMmMtNDU2My1hZmI3LWM4MmY1YjdmNGEwZiJ9.7QBTsoB5C-wEe3fpnYEckP_GFYz9uQR8rcFKXKuRp1NzvMZmtq6O45iivyhgf1QbGDn6QiTu9ppa4r4qKSnUzg',
                'refresh_token' => 'eyJhbGciOiJIUzUxMiIsInYiOiIyLjAiLCJraWQiOiI5NDEyNTQ0NC03ODdjLTRjYWUtYjQ2YS0xZGE3OGRiYzJhMDAifQ.eyJ2ZXIiOjcsImF1aWQiOiI4MTUwNjQwMzNmODM5OTNkOWRmM2U0YTM0ODk2MGY4MiIsImNvZGUiOiJETmhzcG5jTERwQ2ZxY3pqS1dJU1VTLXZfTDlqZ3VYV0EiLCJpc3MiOiJ6bTpjaWQ6NU1kNkRrOWdRNnVGUHZIR3BlX2hPUSIsImdubyI6MCwidHlwZSI6MSwidGlkIjowLCJhdWQiOiJodHRwczovL29hdXRoLnpvb20udXMiLCJ1aWQiOiJtU2dxXzJjWVRuYWY5bUhkUkY2SkN3IiwibmJmIjoxNjcxNDQwNjE5LCJleHAiOjIxNDQ0ODA2MTksImlhdCI6MTY3MTQ0MDYxOSwiYWlkIjoia3RVZUN6ZFVSZXFOWElxMmE4MVQ5QSIsImp0aSI6ImNjMDhjNzhhLWE2MzItNDM4OC1iNTUyLWUxMWRhOTUzOWVhMSJ9.ylEDh9rWKcIWI7Fk-lOtGbx9vxo2T7W63cJulRwXXklUfqYx0aAR81CS9m6a44Ap5lhI8-YHWr-TfClTMMNPGg',
                'token_updated_at' => '2022-12-19 09:03:27',
                'client_id' => '5Md6Dk9gQ6uFPvHGpe_hOQ',
                'client_secret' => 'zq42Gzl4lj8UYZnJBAsiyt5i0qEP46P6'
            ], 200);
        }elseif ($env == 'QA') {
            ZoomCredentials::create([
                'access_token' => 'eyJhbGciOiJIUzUxMiIsInYiOiIyLjAiLCJraWQiOiJmYmY4YmQ4OS1iNzgxLTQ1MmQtOTFkYi00ODQ5ZmNmMTdmZGEifQ.eyJ2ZXIiOjcsImF1aWQiOiI4MTUwNjQwMzNmODM5OTNkOWRmM2U0YTM0ODk2MGY4MiIsImNvZGUiOiJ5SGM1WUlGR1p4RlB2OFJmQ2h1VFJpVlJ5NVFQRGhQOWciLCJpc3MiOiJ6bTpjaWQ6NU1kNkRrOWdRNnVGUHZIR3BlX2hPUSIsImdubyI6MCwidHlwZSI6MCwidGlkIjowLCJhdWQiOiJodHRwczovL29hdXRoLnpvb20udXMiLCJ1aWQiOiJtU2dxXzJjWVRuYWY5bUhkUkY2SkN3IiwibmJmIjoxNjcxNDM0MzAwLCJleHAiOjE2NzE0Mzc5MDAsImlhdCI6MTY3MTQzNDMwMCwiYWlkIjoia3RVZUN6ZFVSZXFOWElxMmE4MVQ5QSIsImp0aSI6IjlhMDQxOWQwLTAzYmUtNDBkOC1iNDBkLWY5YjZlYWJhNTcwYiJ9.YsbD49QeVqFma9UFJPcCk7UWtKZ3E3GCWlioZQw-AYaNuILOlN4iQkOdePUb8J03WpFdLptTqkpkMZzyVJmnQg',
                'refresh_token' => 'eyJhbGciOiJIUzUxMiIsInYiOiIyLjAiLCJraWQiOiJiNTI5OTY4OC1mZDA0LTQ0NzktOTk0Zi1iOTU1YzVkNzdmOGIifQ.eyJ2ZXIiOjcsImF1aWQiOiI4MTUwNjQwMzNmODM5OTNkOWRmM2U0YTM0ODk2MGY4MiIsImNvZGUiOiJ5SGM1WUlGR1p4RlB2OFJmQ2h1VFJpVlJ5NVFQRGhQOWciLCJpc3MiOiJ6bTpjaWQ6NU1kNkRrOWdRNnVGUHZIR3BlX2hPUSIsImdubyI6MCwidHlwZSI6MSwidGlkIjowLCJhdWQiOiJodHRwczovL29hdXRoLnpvb20udXMiLCJ1aWQiOiJtU2dxXzJjWVRuYWY5bUhkUkY2SkN3IiwibmJmIjoxNjcxNDM0MzAwLCJleHAiOjIxNDQ0NzQzMDAsImlhdCI6MTY3MTQzNDMwMCwiYWlkIjoia3RVZUN6ZFVSZXFOWElxMmE4MVQ5QSIsImp0aSI6IjY5NjllOTM2LTMxNWQtNGYzNy1hMjg5LTA0ZWVhYmEwNTA2MCJ9.oUGkPvsunfnQCi6IcbG9Lm4baTwy0GBlpxavThBy5qEbadWVqfDdzvIrN6MgkYdeLV1XER81ZrAIjPuIlJT10A',
                'token_updated_at' => '2022-12-15 06:36:02',
                'client_id' => '5Md6Dk9gQ6uFPvHGpe_hOQ',
                'client_secret' => 'zq42Gzl4lj8UYZnJBAsiyt5i0qEP46P6'
            ], 200);
        }elseif ($env == 'test') {
            ZoomCredentials::create([
                'access_token' => 'eyJhbGciOiJIUzUxMiIsInYiOiIyLjAiLCJraWQiOiJjZWRhY2I0YS1lMjczLTQ5NGItOGQ2Yi03MDAxZTRiMTdlNzkifQ.eyJ2ZXIiOjcsImF1aWQiOiJmMzBmMmUyN2Y0ODllYzc1ZTBhYjY1M2ZkYmExZDdmMiIsImNvZGUiOiJjNm00dThsTDJWc0FpSFlUU0gyUjNHR2M4dTlxVlZOVVEiLCJpc3MiOiJ6bTpjaWQ6SEV4bmFKeVdRRk9OTE9GdVdScHBnIiwiZ25vIjowLCJ0eXBlIjowLCJ0aWQiOjAsImF1ZCI6Imh0dHBzOi8vb2F1dGguem9vbS51cyIsInVpZCI6Imd5Z25XZWZ6UlUyM2N4ZElNR29RWFEiLCJuYmYiOjE2NzEwODYxNjQsImV4cCI6MTY3MTA4OTc2NCwiaWF0IjoxNjcxMDg2MTY0LCJhaWQiOiJlSjh0ekZCS1E4aUdYWG5SYk9FVEFnIiwianRpIjoiNjc1YTdkNTEtODUwNy00MDg3LTgzNTYtZWE4MDQyY2Y2NGQyIn0.xxC3YlltaewDQQrAHVZ-82lxlub5tbbyEEJJusmjRkt4tn48vc1KX5pypf5m8xctlTNuiAVtDXkWC446eyfkMQ',
                'refresh_token' => 'eyJhbGciOiJIUzUxMiIsInYiOiIyLjAiLCJraWQiOiI2YjdiYzNhNi0wY2YwLTQwNWEtYjExMi01YzVjMGI2MDliYTcifQ.eyJ2ZXIiOjcsImF1aWQiOiJmMzBmMmUyN2Y0ODllYzc1ZTBhYjY1M2ZkYmExZDdmMiIsImNvZGUiOiJjNm00dThsTDJWc0FpSFlUU0gyUjNHR2M4dTlxVlZOVVEiLCJpc3MiOiJ6bTpjaWQ6SEV4bmFKeVdRRk9OTE9GdVdScHBnIiwiZ25vIjowLCJ0eXBlIjoxLCJ0aWQiOjAsImF1ZCI6Imh0dHBzOi8vb2F1dGguem9vbS51cyIsInVpZCI6Imd5Z25XZWZ6UlUyM2N4ZElNR29RWFEiLCJuYmYiOjE2NzEwODYxNjQsImV4cCI6MjE0NDEyNjE2NCwiaWF0IjoxNjcxMDg2MTY0LCJhaWQiOiJlSjh0ekZCS1E4aUdYWG5SYk9FVEFnIiwianRpIjoiY2ZiODgzMzQtNTQ0Ni00MDVlLTk0MGUtMTRjMGFhYzg5NDIwIn0.PPtqKvkUBEl9Ifk_cjUaGAMdCKGRKOU2lFbhweJwbW0loumtbOA0K9PG-SvW34fV6tMJwtGt7-1Z2RjtnV7_ZA',
                'token_updated_at' => '2022-12-15 06:36:02',
                'client_id' => '5Md6Dk9gQ6uFPvHGpe_hOQ',
                'client_secret' => 'cq1Mv2E07WNjVuzSUOVkcU8YvyLQ0UJf'
            ], 200);
        }
        
        
    }
}
