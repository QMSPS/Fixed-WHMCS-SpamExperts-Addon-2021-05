<?php
namespace MGModule\ResellersCenter\core;

use MGModule\ResellersCenter\Addon;

/**
 * Description of HookManager
 *
 * @author Paweł Złamaniec <pawel.zl@modulesgarden.com>
 */
class HookManager 
{
    const HOOKS_DIR = __DIR__ . DS . "hooks";
    const HOOKS_NAMESPACE = '\MGModule\ResellersCenter\core\hooks\\';
    
    //Classes name only - not objects!!
    private $hooks = array();
    
    /**
     * Load Hooks
     * 
     * @since 3.0.0
     */
    public function __construct() 
    {
        $this->hooks = $this->getHooksClasses();
        $this->registerHooks();
    }
    
    /**
     * Scan directory in order to find hook classes
     * 
     * @since 3.0.0
     */
    public function getHooksClasses()
    {
        $files = glob(self::HOOKS_DIR.DS."*.php");
        $hooks = array_diff($files, array ('.', '..'));      
        
        foreach($hooks as &$hook)
        {
            $hook = basename(substr($hook, 0, -4));
        }
        
        return $hooks;
    }
    
    /**
     * Register hooks in WHMCS system
     * 
     * @since 3.0.0
     */
    public function registerHooks()
    {
        if(!Addon::I()->configuration()->hooksEnabled)
        {
            return;
        }

        foreach($this->hooks as $hook)
        {
            require self::HOOKS_DIR.DS.$hook.".php";
            
            $classname = self::HOOKS_NAMESPACE.$hook;
            $obj = new $classname();

            /* Preventing foreach PHP warnings */
            if(!is_array($obj->functions) && !is_object($obj->functions))
            {
                continue;
            }

            foreach ($obj->functions as $priority => $func) {
                add_hook($hook, $priority, $func);
            }

        }
    }
}
