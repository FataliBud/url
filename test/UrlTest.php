<?php

namespace League\Url\Test;

use League\Url\Url;
use League\Url\Scheme;
use League\Url\User;
use League\Url\Pass;
use League\Url\Host;
use League\Url\Port;
use League\Url\Path;
use League\Url\Query;
use League\Url\Fragment;
use PHPUnit_Framework_TestCase;

/**
 * @group url
 */
class UrlTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Url
     */
    private $url;

    public function setUp()
    {
        $this->url = Url::createFromUrl(
            'https://login:pass@secure.example.com:443/test/query.php?kingkong=toto#doc3'
        );
    }

    public function tearDown()
    {
        $this->url = null;
    }

    public function testGetterAccess()
    {
        $this->assertInstanceof('League\Url\Scheme', $this->url->getScheme());
        $this->assertInstanceof('League\Url\User', $this->url->getUser());
        $this->assertInstanceof('League\Url\Pass', $this->url->getPass());
        $this->assertInstanceof('League\Url\Host', $this->url->getHost());
        $this->assertInstanceof('League\Url\Port', $this->url->getPort());
        $this->assertInstanceof('League\Url\Path', $this->url->getPath());
        $this->assertInstanceof('League\Url\Query', $this->url->getQuery());
        $this->assertInstanceof('League\Url\Fragment', $this->url->getFragment());
    }

    public function testSetterAccess()
    {
        $this->assertInstanceof('League\Url\Url', $this->url->withScheme('https'));
        $this->assertInstanceof('League\Url\Url', $this->url->withUserInfo('login', 'pass'));
        $this->assertInstanceof('League\Url\Url', $this->url->withHost('secure.example.com'));
        $this->assertInstanceof('League\Url\Url', $this->url->withPort(443));
        $this->assertInstanceof('League\Url\Url', $this->url->withPath('/test/query.php'));
        $this->assertInstanceof('League\Url\Url', $this->url->withQuery('?kingkong=toto'));
        $this->assertInstanceof('League\Url\Url', $this->url->withFragment('doc3'));
    }

    public function testGetBaseUrl()
    {
        $this->assertSame('https://login:pass@secure.example.com', $this->url->getBaseUrl());
    }

    public function testGetAuthority()
    {
        $this->assertSame('login:pass@secure.example.com', $this->url->getAuthority());
    }

    public function testGetUserInfo()
    {
        $this->assertSame('login:pass', $this->url->getUserInfo());
    }

    public function testAutomaticUrlNormalization()
    {
        $url = Url::createFromUrl(
            'HtTpS://MaStEr.eXaMpLe.CoM:83/%7ejohndoe/%a1/index.php?foo.bar=value#fragment'
        );

        $this->assertSame(
            'https://master.example.com:83/~johndoe/%A1/index.php?foo.bar=value#fragment',
            (string) $url
        );
    }

    public function testToArray()
    {
        $url = Url::createFromUrl('https://toto.com:443/toto.php');
        $this->assertSame([
            'scheme' => 'https',
            'user' => null,
            'pass' => null,
            'host' => 'toto.com',
            'port' => 443,
            'path' => 'toto.php',
            'query' => null,
            'fragment' => null,
        ], $url->toArray());
    }

    public function testEmptyConstructor()
    {
        $url = new Url(
            new Scheme(),
            new User(),
            new Pass(),
            new Host(),
            new Port(),
            new Path(),
            new Query(),
            new Fragment()
        );

        $this->assertEmpty($url->__toString());
    }

    public function testSameValueAs()
    {
        $url1 = new Url(
            new Scheme(),
            new User(),
            new Pass(),
            new Host('example.com'),
            new Port(),
            new Path(),
            new Query(),
            new Fragment()
        );

        $url2 = new Url(
            new Scheme(),
            new User(),
            new Pass(),
            new Host('ExAmPLe.cOm'),
            new Port(),
            new Path(),
            new Query(),
            new Fragment()
        );

        $this->assertTrue($url1->sameValueAs($url2));
    }
}
