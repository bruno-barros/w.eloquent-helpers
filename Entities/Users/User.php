<?php namespace App\Entities\Users;

use App\Support\Formatters\CpfFormat;
use App\Support\Formatters\PhoneFormat;
use Illuminate\Support\Collection;

/**
 * User
 *
 * @author       Bruno Barros  <bruno@brunobarros.com>
 * @copyright    Copyright (c) 2015 Bruno Barros
 */
class User extends \WP_User_Query
{
    private $collection = null;


    protected $metafields = [
        'data_nascimento',
        'endereco',
        'numero',
        'complemento',
        'bairro',
        'cep',
        'cidade',
        'uf',
        'escolaridade',
        'curso',
        'identidade',
        'orgao_exp',
        'cpf',
        'telefone',
        'celular',
        'empresa',
        'empresa_cnpj',
        'empresa_endereco',
        'empresa_numero',
        'empresa_complemento',
        'empresa_bairro',
        'empresa_cep',
        'empresa_cidade',
        'empresa_uf',
        'empresa_telefone',
        'empresa_fax',
        'empresa_email',
    ];


    public static function create($attributes = [])
    {
        $self = new static;

        $attributes = $self->prepareFields($attributes, 'creation');

        $user_id = wp_insert_user($attributes);

        if (is_wp_error($user_id) || $user_id == 0)
        {
            return $user_id;
        }

        $self->addOrUpdateMetafields($user_id, $attributes);

        return $user_id;

    }


    /**
     * @param \Illuminate\Support\Collection $collection
     * @param array                          $attributes
     *
     * @return bool|int|\WP_Error
     */
    public static function update(Collection $collection, $attributes = [])
    {
        $user = $collection->first();

        $attr = [
            'ID' => $user->ID
        ];

        $self = new static;

        $attributes = $self->prepareFields($attributes, 'updating');

        $user_id = wp_update_user(array_merge($attributes, $attr));

        if (is_wp_error($user_id))
        {
            return $user_id;
        }

        $self->addOrUpdateMetafields($user->ID, $attributes);

        return true;
    }


    /**
     * Update meta fields registered on $this->metafields
     *
     * @param       $userId
     * @param array $attributes
     */
    public function addOrUpdateMetafields($userId, $attributes = [])
    {
        foreach ($attributes as $key => $value)
        {
            if (in_array($key, $this->metafields))
            {
                update_user_meta($userId, $key, $this->prepareMetaValue($key, $value));
            }
        }
    }





    public static function findByCpf($cpf = '')
    {
        $metaCpf = CpfFormat::make($cpf)->format('99999999999');

        $user_query = new static([
            'meta_key'   => 'cpf',
            'meta_value' => $metaCpf,
        ]);

        return Collection::make($user_query->get_results());
    }


    public static function findBySnqc($snqc = '')
    {
        $user_query = new static([
            'meta_key'   => 'snqc',
            'meta_value' => $snqc,
        ]);

        return Collection::make($user_query->get_results());
    }


    public static function findByEmail($email = '')
    {

        $args = array(
            'search'         => $email,
            'search_columns' => array('user_email')
        );

        $user_query = new static($args);

        return Collection::make($user_query->get_results());
    }


    /**
     * Prepare needed fields to create and update
     *
     * @param array  $attr
     *
     * @param string $action creation || updating
     *
     * @return mixed
     */
    protected function prepareFields($attr, $action = 'creation')
    {
        if (isset($attr['nome']))
        {
            $attr['first_name'] = $attr['nome'];
        }

        if (isset($attr['senha']))
        {
            $attr['user_pass'] = wp_hash_password($attr['senha']);
        }
        // if not already, use cpf as password
        if (!isset($attr['user_pass']) && isset($attr['cpf']) && $action == 'creation')
        {
            $attr['user_pass'] = wp_hash_password($attr['cpf']);
        }

        if (isset($attr['email']))
        {
            $attr['user_login'] = $attr['email'];
            $attr['user_email'] = $attr['email'];
        }

        if(isset($attr['cpf']))
        {
            $attr['cpf'] = CpfFormat::make($attr['cpf'])->format('99999999999');
        }

        if(isset($attr['telefone']))
        {
            $attr['telefone'] = PhoneFormat::make($attr['telefone'])->get();
        }
        if(isset($attr['celular']))
        {
            $attr['celular'] = PhoneFormat::make($attr['celular'])->get();
        }
        if(isset($attr['empresa_telefone']))
        {
            $attr['empresa_telefone'] = PhoneFormat::make($attr['empresa_telefone'])->get();
        }
        if(isset($attr['empresa_fax']))
        {
            $attr['empresa_fax'] = PhoneFormat::make($attr['empresa_fax'])->get();
        }

        return $attr;
    }


    protected function prepareMetaValue($key, $value)
    {

        if ($key === 'cpf')
        {
            $value = CpfFormat::make($value)->format('99999999999');
        }

        return $value;
    }

}