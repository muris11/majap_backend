 <?php
 
 namespace App\Services\Schema;
 
 class FormSchemaBuilder
 {
     protected array $fields = [];
     protected array $sections = [];
     protected array $validation = [];
 
     public static function make(): self
     {
         return new self();
     }
 
     public function text(string $name, string $label, array $options = []): self
     {
         return $this->addField($name, FieldType::TEXT, $label, $options);
     }
 
     public function textarea(string $name, string $label, array $options = []): self
     {
         return $this->addField($name, FieldType::TEXTAREA, $label, $options);
     }
 
     public function richtext(string $name, string $label, array $options = []): self
     {
         return $this->addField($name, FieldType::RICHTEXT, $label, $options);
     }
 
     public function number(string $name, string $label, array $options = []): self
     {
         return $this->addField($name, FieldType::NUMBER, $label, $options);
     }
 
     public function email(string $name, string $label, array $options = []): self
     {
         return $this->addField($name, FieldType::EMAIL, $label, $options);
     }
 
     public function password(string $name, string $label, array $options = []): self
     {
         return $this->addField($name, FieldType::PASSWORD, $label, $options);
     }
 
     public function select(string $name, string $label, array $options = []): self
     {
         return $this->addField($name, FieldType::SELECT, $label, $options);
     }
 
     public function multiSelect(string $name, string $label, array $options = []): self
     {
         return $this->addField($name, FieldType::MULTI_SELECT, $label, $options);
     }
 
     public function checkbox(string $name, string $label, array $options = []): self
     {
         return $this->addField($name, FieldType::CHECKBOX, $label, $options);
     }
 
     public function toggle(string $name, string $label, array $options = []): self
     {
         return $this->addField($name, FieldType::TOGGLE, $label, $options);
     }
 
     public function date(string $name, string $label, array $options = []): self
     {
         return $this->addField($name, FieldType::DATE, $label, $options);
     }
 
     public function datetime(string $name, string $label, array $options = []): self
     {
         return $this->addField($name, FieldType::DATETIME, $label, $options);
     }
 
     public function time(string $name, string $label, array $options = []): self
     {
         return $this->addField($name, FieldType::TIME, $label, $options);
     }
 
     public function file(string $name, string $label, array $options = []): self
     {
         return $this->addField($name, FieldType::FILE, $label, $options);
     }
 
     public function image(string $name, string $label, array $options = []): self
     {
         return $this->addField($name, FieldType::IMAGE, $label, $options);
     }
 
     public function enum(string $name, string $label, string $enumKey, array $options = []): self
     {
         $options['enum_key'] = $enumKey;
         return $this->addField($name, FieldType::ENUM, $label, $options);
     }
 
     public function relation(string $name, string $label, string $endpoint, array $options = []): self
     {
         $options['options_endpoint'] = $endpoint;
         return $this->addField($name, FieldType::RELATION, $label, $options);
     }
 
     public function hidden(string $name, array $options = []): self
     {
         return $this->addField($name, FieldType::HIDDEN, '', $options);
     }
 
     public function section(string $title, array $fields, ?string $description = null): self
     {
         $this->sections[] = [
             'title' => $title,
             'description' => $description,
             'fields' => $fields,
         ];
 
         return $this;
     }
 
     protected function addField(string $name, FieldType $type, string $label, array $options): self
     {
         $field = [
             'name' => $name,
             'type' => $type->value,
             'label' => $label,
         ];
 
         $allowedOptions = [
             'placeholder', 'required', 'disabled', 'readonly', 'default',
             'min', 'max', 'minLength', 'maxLength', 'pattern', 'step',
             'options', 'options_endpoint', 'enum_key', 'multiple',
             'accept', 'maxSize', 'rows', 'cols',
             'grid', 'hint', 'prefix', 'suffix',
             'displayField', 'valueField', 'searchable',
         ];
 
         foreach ($allowedOptions as $option) {
             if (isset($options[$option])) {
                 if ($option === 'grid') {
                     $field['grid'] = $options[$option];
                 } elseif (in_array($option, ['min', 'max', 'minLength', 'maxLength', 'pattern'])) {
                     $field['validation'][$option] = $options[$option];
                 } else {
                     $field[$option] = $options[$option];
                 }
             }
         }
 
         $this->fields[$name] = $field;
 
         return $this;
     }
 
     public function toArray(): array
     {
         return [
             'fields' => array_values($this->fields),
             'sections' => $this->sections,
         ];
     }
 
     public function getEnumKeys(): array
     {
         $keys = [];
 
         foreach ($this->fields as $field) {
             if (isset($field['enum_key'])) {
                 $keys[] = $field['enum_key'];
             }
         }
 
         return array_unique($keys);
     }
 }
