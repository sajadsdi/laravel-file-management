<?php

namespace Sajadsdi\LaravelFileManagement\Enums;

enum FileType: int
{
    case image = 1001;
    case video = 1002;
    case music = 1003;
    case document = 1004;
    case program = 1005;
    case compress = 1006;
    case other = 1007;
}
