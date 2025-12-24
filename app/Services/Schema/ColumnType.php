 <?php
 
 namespace App\Services\Schema;
 
 enum ColumnType: string
 {
     case TEXT = 'text';
     case NUMBER = 'number';
     case DATE = 'date';
     case DATETIME = 'datetime';
     case BADGE = 'badge';
     case IMAGE = 'image';
     case BOOLEAN = 'boolean';
     case LINK = 'link';
     case ACTIONS = 'actions';
     case CUSTOM = 'custom';
 }
