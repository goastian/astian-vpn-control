<?php

namespace App\Models\Setting;

use App\Models\Master;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;
use App\Transformers\Admin\SettingTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Master
{
    use HasFactory;

    public $table = "settings";


    public $transformer = SettingTransformer::class;

    public $timestamps = false;

    protected $fillable = [
        'key',
        'value',
        'user_id',
        'group'
    ];

    /**
     * Set default values 
     * @return void
     */
    public static function defaultSetting()
    {
        if ($item = config('system.schema_mode', 'https')) {
            URL::forceScheme($item);
        }

        Config::set('app.name', settingItem('app.name', 'VPN Server'));
        Config::set('app.org_name', settingItem('app.org_name', 'Elyerr Org'));

        //Plan config
        Config::set('vpn.free', settingItem('vpn.free', 1));
        Config::set('vpn.basic', settingItem('vpn.basic', 2));
        Config::set('vpn.intermediate', settingItem('vpn.intermediate', 5));
        Config::set('vpn.advanced', settingItem('vpn.advanced', 10));

        Setting::getRedisConfig();
        Setting::getQueueSetting();
        Setting::getSystemSetting();
        Setting::getSessionSettings();
    }

    /**
     * Set default values
     * @return void
     */
    public static function setDefaultKeys()
    {
        //App name
        settingLoad('app.name', 'Vpn Server');
        settingLoad('app.org_name', 'Server org');

        //------------------------REDIS CONFIGURATION-------------------//
        //redis default settings
        settingLoad('database.redis.default.url', null);
        settingLoad('database.redis.default.host', '127.0.0.1');
        settingLoad('database.redis.default.username', null);
        settingLoad('database.redis.default.password', null);
        settingLoad('database.redis.default.port', '6379');
        settingLoad('database.redis.default.database', 0);
        //redis cache settings
        settingLoad('database.redis.cache.url', null);
        settingLoad('database.redis.cache.host', '127.0.0.1');
        settingLoad('database.redis.cache.username', null);
        settingLoad('database.redis.cache.password', null);
        settingLoad('database.redis.cache.port', '6379');
        settingLoad('database.redis.cache.database', 1);


        //---------------------QUEUES CONFIG--------------------///
        //default queues
        settingLoad('queue.default', 'database');

        //Sync setting
        settingLoad('queue.connections.sync.driver', 'sync');

        //Database settings
        settingLoad('queue.connections.database.driver', 'database');
        settingLoad('queue.connections.database.table', 'jobs');
        settingLoad('queue.connections.database.queue', 'default');
        settingLoad('queue.connections.database.retry_after', 90);
        settingLoad('queue.connections.database.after_commit', false);

        //beanstalkd Settings
        settingLoad('queue.connections.beanstalkd.driver', 'beanstalkd');
        settingLoad('queue.connections.beanstalkd.host', 'localhost');
        settingLoad('queue.connections.beanstalkd.queue', 'default');
        settingLoad('queue.connections.beanstalkd.retry_after', 90);
        settingLoad('queue.connections.beanstalkd.block_for', 0);
        settingLoad('queue.connections.beanstalkd.after_commit', false);

        //AWS settings
        settingLoad('queue.connections.sqs.driver', 'sqs');
        settingLoad('queue.connections.sqs.key', null);
        settingLoad('queue.connections.sqs.secret', null);
        settingLoad('queue.connections.sqs.prefix', 'https://sqs.us-east-1.amazonaws.com/your-account-id');
        settingLoad('queue.connections.sqs.queue', 'default');
        settingLoad('queue.connections.sqs.suffix', null);
        settingLoad('queue.connections.sqs.region', 'us-east-1');
        settingLoad('queue.connections.sqs.after_commit', false);

        //Redis Settings
        settingLoad('queue.connections.redis.driver', 'redis');
        settingLoad('queue.connections.redis.connection', 'default');
        settingLoad('queue.connections.redis.queue', 'default');
        settingLoad('queue.connections.redis.retry_after', 90);
        settingLoad('queue.connections.redis.block_for', null);
        settingLoad('queue.connections.redis.after_commit', false);

        //Fail queue settings
        settingLoad('queue.failed.driver', 'database-uuids');
        settingLoad('queue.failed.database', 'mysql');
        settingLoad('queue.failed.table', 'failed_jobs');

        //System settings
        settingLoad('system.schema_mode', "https");
        settingLoad('system.home_page', "/");
        settingLoad('system.csp_enabled', false);
        settingLoad('system.redirect_to', "/account");

        //Session settings
        settingLoad('session.driver', 'database');
        settingLoad('session.encrypt', false);
        settingLoad('session.table', 'sessions');
        settingLoad('session.cookie', 'vpn_server_session');
        settingLoad('session.xcsrf-token', 'vpn_server_csrf');
        settingLoad('session.path', '/');
        settingLoad('session.secure', true);
        settingLoad('session.http_only', true);
        settingLoad('session.partitioned', false);
    }


    /**
     * Redis configuration
     * @return void
     */
    public static function getRedisConfig()
    {
        Config::set('database.redis.default.url', settingItem('database.redis.default.url', null));
        Config::set('database.redis.default.host', settingItem('database.redis.default.host', '6379'));
        Config::set('database.redis.default.username', settingItem('database.redis.default.username', null));
        Config::set('database.redis.default.password', settingItem('database.redis.default.password', null));
        Config::set('database.redis.default.port', settingItem('database.redis.default.port', '127.0.0.1'));
        Config::set('database.redis.default.database', settingItem('database.redis.default.database', 0));

        Config::set('database.redis.cache.url', settingItem('database.redis.cache.url', null));
        Config::set('database.redis.cache.host', settingItem('database.redis.cache.host', '6379'));
        Config::set('database.redis.cache.username', settingItem('database.redis.cache.username', null));
        Config::set('database.redis.cache.password', settingItem('database.redis.cache.password', null));
        Config::set('database.redis.cache.port', settingItem('database.redis.cache.port', '127.0.0.1'));
        Config::set('database.redis.cache.database', settingItem('database.redis.cache.database', 1));

    }

    /**
     * Loading default queue settings
     * @return void
     */
    public static function getQueueSetting()
    {
        //default queues
        Config::set('queue.default', settingItem('queue.default', 'sync'));

        //Sync setting
        Config::set('queue.connections.sync.driver', settingItem('queue.connections.sync.driver', 'sync'));

        //Database settings
        Config::set('queue.connections.sync.driver', settingItem('queue.connections.database.driver', 'database'));
        Config::set('queue.connections.sync.table', settingItem('queue.connections.database.table', 'jobs'));
        Config::set('queue.connections.sync.queue', settingItem('queue.connections.database.queue', 'default'));
        Config::set('queue.connections.sync.retry_after', settingItem('queue.connections.database.retry_after', 90));
        Config::set('queue.connections.sync.after_commit', settingItem('queue.connections.database.after_commit', false));

        //beanstalkd Settings
        Config::set('queue.connections.beanstalkd.driver', settingItem('queue.connections.beanstalkd.driver', 'beanstalkd'));
        Config::set('queue.connections.beanstalkd.host', settingItem('queue.connections.beanstalkd.host', 'localhost'));
        Config::set('queue.connections.beanstalkd.queue', settingItem('queue.connections.beanstalkd.queue', 'default'));
        Config::set('queue.connections.beanstalkd.retry_after', settingItem('queue.connections.beanstalkd.retry_after', 90));
        Config::set('queue.connections.beanstalkd.block_for', settingItem('queue.connections.beanstalkd.block_for', 0));
        Config::set('queue.connections.beanstalkd.after_commit', settingItem('queue.connections.beanstalkd.after_commit', false));

        //AWS settings
        Config::set('queue.connections.sqs.driver', settingItem('queue.connections.sqs.driver', 'sqs'));
        Config::set('queue.connections.sqs.key', settingItem('queue.connections.sqs.key', null));
        Config::set('queue.connections.sqs.secret', settingItem('queue.connections.sqs.secret', null));
        Config::set('queue.connections.sqs.prefix', settingItem('queue.connections.sqs.prefix', 'https://sqs.us-east-1.amazonaws.com/your-account-id'));
        Config::set('queue.connections.sqs.queue', settingItem('queue.connections.sqs.queue', 'default'));
        Config::set('queue.connections.sqs.suffix', settingItem('queue.connections.sqs.suffix', null));
        Config::set('queue.connections.sqs.region', settingItem('queue.connections.sqs.region', 'us-east-1'));
        Config::set('queue.connections.sqs.after_commit', settingItem('queue.connections.sqs.after_commit', false));

        //Redis Settings
        Config::set('queue.connections.redis.driver', settingItem('queue.connections.redis.driver', 'redis'));
        Config::set('queue.connections.redis.connection', settingItem('queue.connections.redis.connection', 'default'));
        Config::set('queue.connections.redis.queue', settingItem('queue.connections.redis.queue', 'default'));
        Config::set('queue.connections.redis.retry_after', settingItem('queue.connections.redis.retry_after', 90));
        Config::set('queue.connections.redis.block_for', settingItem('queue.connections.redis.block_for', null));
        Config::set('queue.connections.redis.after_commit', settingItem('queue.connections.redis.after_commit', false));

        //Fail queue settings
        Config::set('queue.failed.driver', settingItem('queue.failed.driver', 'database-uuids'));
        Config::set('queue.failed.database', settingItem('queue.failed.database', 'mysql'));
        Config::set('queue.failed.table', settingItem('queue.failed.table', 'failed_jobs'));
    }

    /**
     * Setting system
     * @return void
     */
    public static function getSystemSetting()
    {
        Config::set('system.schema_mode', settingItem('system.schema_mode', "https"));
        Config::set('system.home_page', settingItem('system.home_page', "/"));
        Config::set('system.cookie_name', settingItem('system.cookie_name', null));
        Config::set('system.csp_enabled', settingItem('system.csp_enabled', false));
        Config::set('system.redirect_to', settingItem('system.redirect_to', null));
    }

    /**
     * Summary of getSessionSettings
     * @return void
     */
    public static function getSessionSettings()
    {
        Config::set('session.driver', settingItem('session.driver', 'database'));
        Config::set('session.encrypt', settingItem('session.encrypt', false));
        Config::set('session.table', settingItem('session.table', 'sessions'));
        Config::set('session.cookie', settingItem('session.cookie', 'vpn_server_session'));
        Config::set('session.xcsrf-token', settingItem('session.xcsrf-token', 'vpn_server_csrf'));
        Config::set('session.path', settingItem('session.path', '/'));
        Config::set('session.secure', settingItem('session.secure', true));
        Config::set('session.http_only', settingItem('session.http_only', true));
        Config::set('session.partitioned', settingItem('session.partitioned', false));
    }
}
