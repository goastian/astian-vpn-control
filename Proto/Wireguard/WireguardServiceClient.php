<?php
// GENERATED CODE -- DO NOT EDIT!

namespace Proto\Wireguard;

/**
 */
class WireguardServiceClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \Proto\Wireguard\MountRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function mount(\Proto\Wireguard\MountRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/proto.wireguard.WireguardService/mount',
        $argument,
        ['\Proto\Wireguard\MessageResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Proto\Wireguard\InterfaceRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function umount(\Proto\Wireguard\InterfaceRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/proto.wireguard.WireguardService/umount',
        $argument,
        ['\Proto\Wireguard\MessageResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Proto\Wireguard\InterfaceRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function down(\Proto\Wireguard\InterfaceRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/proto.wireguard.WireguardService/down',
        $argument,
        ['\Proto\Wireguard\MessageResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Proto\Wireguard\InterfaceRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function up(\Proto\Wireguard\InterfaceRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/proto.wireguard.WireguardService/up',
        $argument,
        ['\Proto\Wireguard\MessageResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Proto\Wireguard\InterfaceRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function restart(\Proto\Wireguard\InterfaceRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/proto.wireguard.WireguardService/restart',
        $argument,
        ['\Proto\Wireguard\MessageResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Proto\Wireguard\DnsRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function dns(\Proto\Wireguard\DnsRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/proto.wireguard.WireguardService/dns',
        $argument,
        ['\Proto\Wireguard\MessageResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Proto\Wireguard\AddPeerRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function addPeer(\Proto\Wireguard\AddPeerRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/proto.wireguard.WireguardService/addPeer',
        $argument,
        ['\Proto\Wireguard\MessageResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Proto\Wireguard\DeletePeerRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function deletePeer(\Proto\Wireguard\DeletePeerRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/proto.wireguard.WireguardService/deletePeer',
        $argument,
        ['\Proto\Wireguard\MessageResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Proto\Wireguard\EmptyRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function interfaces(\Proto\Wireguard\EmptyRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/proto.wireguard.WireguardService/interfaces',
        $argument,
        ['\Proto\Wireguard\ListInterfacesResponse', 'decode'],
        $metadata, $options);
    }

}
