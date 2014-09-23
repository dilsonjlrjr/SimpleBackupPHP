<?php

namespace InfraEstrutura\System;

/**
 * Description of System
 *
 * @author nme
 */
class OS {
    Const __Darwin = "DARWIN",
          __FreeBSD = "FREEBSD",
          __HpUX = "HP-UX",
          __IRIX64 = "IRIX64",
          __Linux = "LINUX",
          __NetBSD = "NETBSD",
          __OpenBSD = "OPENBSD",
          __SunOS = "SUNOS",
          __Unix = "UNIX",
          __WIN32 = "WIN32",
          __WINNT = "WINNT",
          __Windows = "WINDOWS";
    
    public static function getSistemaOperacional(){
        return strtoupper(PHP_OS);
    }
}
