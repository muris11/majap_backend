 <?php
 
 namespace App\Services\Schema;
 
 enum FieldType: string
 {
     case TEXT = 'text';
     case TEXTAREA = 'textarea';
     case RICHTEXT = 'richtext';
     case NUMBER = 'number';
     case EMAIL = 'email';
     case PASSWORD = 'password';
     case SELECT = 'select';
     case MULTI_SELECT = 'multi_select';
     case CHECKBOX = 'checkbox';
     case TOGGLE = 'toggle';
     case DATE = 'date';
     case DATETIME = 'datetime';
     case TIME = 'time';
     case FILE = 'file';
     case IMAGE = 'image';
     case ENUM = 'enum';
     case RELATION = 'relation';
     case HIDDEN = 'hidden';
 }
