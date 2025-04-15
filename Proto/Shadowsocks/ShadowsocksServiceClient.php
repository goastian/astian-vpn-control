<?php
// GENERATED CODE -- DO NOT EDIT!

namespace Proto\Shadowsocks;

/**
 */
class ShadowsocksServiceClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \Proto\Shadowsocks\MountRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function mount(\Proto\Shadowsocks\MountRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/proto.shadowsocks.ShadowsocksService/mount',
        $argument,
        ['\Proto\Shadowsocks\MessageResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Proto\Shadowsocks\EmptyRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function delete(\Proto\Shadowsocks\EmptyRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/proto.shadowsocks.ShadowsocksService/delete',
        $argument,
        ['\Proto\Shadowsocks\MessageResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Proto\Shadowsocks\EmptyRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function ServerStart(\Proto\Shadowsocks\EmptyRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/proto.shadowsocks.ShadowsocksService/ServerStart',
        $argument,
        ['\Proto\Shadowsocks\MessageResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Proto\Shadowsocks\EmptyRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function ServerStatus(\Proto\Shadowsocks\EmptyRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/proto.shadowsocks.ShadowsocksService/ServerStatus',
        $argument,
        ['\Proto\Shadowsocks\MessageResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Proto\Shadowsocks\EmptyRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function ServerStop(\Proto\Shadowsocks\EmptyRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/proto.shadowsocks.ShadowsocksService/ServerStop',
        $argument,
        ['\Proto\Shadowsocks\MessageResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Proto\Shadowsocks\EmptyRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function ClientStart(\Proto\Shadowsocks\EmptyRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/proto.shadowsocks.ShadowsocksService/ClientStart',
        $argument,
        ['\Proto\Shadowsocks\MessageResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Proto\Shadowsocks\EmptyRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function ClientStatus(\Proto\Shadowsocks\EmptyRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/proto.shadowsocks.ShadowsocksService/ClientStatus',
        $argument,
        ['\Proto\Shadowsocks\MessageResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Proto\Shadowsocks\EmptyRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function ClientStop(\Proto\Shadowsocks\EmptyRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/proto.shadowsocks.ShadowsocksService/ClientStop',
        $argument,
        ['\Proto\Shadowsocks\MessageResponse', 'decode'],
        $metadata, $options);
    }

}
