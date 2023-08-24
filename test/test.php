<?php


function baiDuFanYi($string)
{
    $header = [
        'Accept'             => '*/*',
        'Accept-Encoding'    => 'gzip, deflate, br',
        'Accept-Language'    => 'zh-CN,zh;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6',
        'Acs-Token'          => '1692122405710_1692177054320_dBDtlGnM3g7rO8wvjVyLLImO6V0rnNl1tufN6Kz2rhOMEnxZtBiu7MNCpURA6fytAFz+Iu4GXqwy1MxShKYTix9kt5Pi8cMMmL5MaBjtmbPo7J8McA3up25kfXy9D9fPlfQm6C8O2S6HoDuURoOSbtvErnhICEqR9fGy8+cgWJFf4EucTFsS6uNpJYbBks2R2uq2lF0WEgrJgMQuHCL8EnYaoHh9oyB0rjf4IcudAGFGZIk+6YUw9u3EcYGmty0XYaSat+jHN6fCemWQVScj4dqs2LbHSs3jrqV+jZF3TYB5LG3G8CdSDwfeHUG1aZ0Uc/2BL2QxW0/ERTOd2SEaqWRaH6gd2pl0Vbdno0wQO1y1MGIVW39aQxayPYgEy0Pl/BGa2gkJmf/blD9lYzDCtUDk0NkZEwMAxqEmMcBRiUgLPY16XF+rc7e3OvDJ1B5cHTvSa4VgmXiwb+YuJJgoWKGlfu+r1MzocdoDXfO8EzTvgJeCjkcz8uNhLFJeP1QZaFH1Lyce4J1vYczszJ0S4g==',
        'Cache-Control'      => 'no-cache',
        'Connection'         => 'keep-alive',
        'Content-Length'     => '166',
        'Content-Type'       => 'application/x-www-form-urlencoded; charset=UTF-8',
        'Cookie'             => 'BIDUPSID=54BBD830602F961522F73CE2955FC622; PSTM=1681380120; BAIDUID=54BBD830602F96159DD2A4D2C72C09AF=>FG=1; REALTIME_TRANS_SWITCH=1; FANYI_WORD_SWITCH=1; HISTORY_SWITCH=1; SOUND_SPD_SWITCH=1; SOUND_PREFER_SWITCH=1; MCITY=-340%3A; BDUSS=0lKWUlzdHB2ZTVDSmlxeTJLc2VURzg3Z3BrRmljeExSaENLby1GSEFxS0E2dk5rSVFBQUFBJCQAAAAAAAAAAAEAAAAMZ0um0KHQobH40KHOsAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAIBdzGSAXcxkd; BDUSS_BFESS=0lKWUlzdHB2ZTVDSmlxeTJLc2VURzg3Z3BrRmljeExSaENLby1GSEFxS0E2dk5rSVFBQUFBJCQAAAAAAAAAAAEAAAAMZ0um0KHQobH40KHOsAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAIBdzGSAXcxkd; H_WISE_SIDS=234020_110085_259312_266324_266759_265887_268181_268592_259642_269390_256151_269554_188333_269731_269783_269831_269904_270181_269719_270966_271036_271019_268875_271171_271174_267659_271319_271271_269892_271471_269609_270102_271562; H_WISE_SIDS_BFESS=234020_110085_259312_266324_266759_265887_268181_268592_259642_269390_256151_269554_188333_269731_269783_269831_269904_270181_269719_270966_271036_271019_268875_271171_271174_267659_271319_271271_269892_271471_269609_270102_271562; delPer=0; PSINO=6; BAIDUID_BFESS=54BBD830602F96159DD2A4D2C72C09AF=>FG=1; BA_HECTOR=a40ha52l0g24a0a48420ak8l1idp2jr1o; ZFY=3JhcgZVUwa9RkxmfBqhOOmTXg6004TV8ujH96zSl0d0=>C; H_PS_PSSID=36560_39109_39198_26350_39138_39092_39137_39101; Hm_lvt_64ecd82404c51e03dc91cb9e8c025574=1691738545,1692071711,1692152021,1692177042; Hm_lpvt_64ecd82404c51e03dc91cb9e8c025574=1692177042',
        'Host'               => 'fanyi.baidu.com',
        'Origin'             => 'https=>//fanyi.baidu.com',
        'Pragma'             => 'no-cache',
        'Referer'            => 'https=>//fanyi.baidu.com/?aldtype=16047',
        'Sec-Ch-Ua'          => '"Not/A)Brand";v="99", "Microsoft Edge";v="115", "Chromium";v="115"',
        'Sec-Ch-Ua-Mobile'   => '?0',
        'Sec-Ch-Ua-Platform' => 'Windows',
        'Sec-Fetch-Dest'     => 'empty',
        'Sec-Fetch-Mode'     => 'cors',
        'Sec-Fetch-Site'     => 'same-origin',
        'User-Agent'         => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36 Edg/115.0.1901.203',
        'X-Requested-With'   => 'XMLHttpRequest',
    ];

    $data = [
        'from'              => 'zh',
        'to'                => 'en',
        'query'             => $string,
        'transtype'         => 'realtime',
        'simple_means_flag' => 3,
        'sign'              => 232427.485594,
        'token'             => '130b5ba62633ab009f958396d6dc8822',
        'domain'            => 'common',
        'ts'                => '1692177054303',

    ];

    $ch = curl_init('https=>//fanyi.baidu.com/v2transapi?from=zh&to=en');
    curl_setopt($ch, CURLOPT_HEADEROPT, $header);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $result = curl_exec($ch);
    curl_close($ch);
    var_dump($result);
}

baiDuFanYi("你好");







