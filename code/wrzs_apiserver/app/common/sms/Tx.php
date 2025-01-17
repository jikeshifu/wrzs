<?php


namespace app\common\sms;


use TencentCloud\Sms\V20210111\SmsClient;

// 导入要请求接口对应的Request类
use TencentCloud\Sms\V20210111\Models\SendSmsRequest;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Common\Credential;

// 导入可选配置类
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;

class Tx
{
    /**
     * @param array $PhoneNumberSet 手机号码
     * @param $SessionContext 短信内容
     * @param $TemplateId 模板 ID
     * @param array $TemplateParamSet 模板参数
     */
    static function smsPush($PhoneNumberSet = [], $TemplateId, $TemplateParamSet = [])
    {

        return false;
        $cred = new Credential("", "");

        // 实例化一个http选项，可选的，没有特殊需求可以跳过
        $httpProfile = new HttpProfile();

        // 配置代理
        // $httpProfile->setProxy("https://ip:port");
        $httpProfile->setReqMethod("GET");  // post请求(默认为post请求)
        $httpProfile->setReqTimeout(30);    // 请求超时时间，单位为秒(默认60秒)
        $httpProfile->setEndpoint("sms.tencentcloudapi.com");  // 指定接入地域域名(默认就近接入)

        // 实例化一个client选项，可选的，没有特殊需求可以跳过
        $clientProfile = new ClientProfile();

        $clientProfile->setSignMethod("TC3-HMAC-SHA256");  // 指定签名算法(默认为HmacSHA256)
        $clientProfile->setHttpProfile($httpProfile);

        // 实例化要请求产品(以sms为例)的client对象,clientProfile是可选的
        // 第二个参数是地域信息，可以直接填写字符串 ap-guangzhou，或者引用预设的常量
        $client = new SmsClient($cred, "ap-guangzhou", $clientProfile);

        // 实例化一个 sms 发送短信请求对象,每个接口都会对应一个request对象。
        $req = new SendSmsRequest();

        /* 填充请求参数,这里request对象的成员变量即对应接口的入参
         * 你可以通过官网接口文档或跳转到request对象的定义处查看请求参数的定义
         * 基本类型的设置:
         * 帮助链接：
         * 短信控制台: https://console.cloud.tencent.com/smsv2
         * sms helper: https://cloud.tencent.com/document/product/382/3773 */

        /* 短信应用ID: 短信SdkAppId在 [短信控制台] 添加应用后生成的实际SdkAppId，示例如1400006666 */
        $req->SmsSdkAppId = "1400601222";
        /* 短信签名内容: 使用 UTF-8 编码，必须填写已审核通过的签名，签名信息可登录 [短信控制台] 查看 */
        $req->SignName = "小慢十二月";
        /* 短信码号扩展号: 默认未开通，如需开通请联系 [sms helper] */
        $req->ExtendCode = "";
        /* 下发手机号码，采用 E.164 标准，+[国家或地区码][手机号]
         * 示例如：+8613711112222， 其中前面有一个+号 ，86为国家码，13711112222为手机号，最多不要超过200个手机号*/
        $req->PhoneNumberSet = $PhoneNumberSet;
        /* 国际/港澳台短信 SenderId: 国内短信填空，默认未开通，如需开通请联系 [sms helper] */
        $req->SenderId = "";
        /* 用户的 session 内容: 可以携带用户侧 ID 等上下文信息，server 会原样返回 */
        $req->SessionContext = '';
        /* 模板 ID: 必须填写已审核通过的模板 ID。模板ID可登录 [短信控制台] 查看 */
        $req->TemplateId = $TemplateId;
        /* 模板参数: 若无模板参数，则设置为空*/
        $req->TemplateParamSet = $TemplateParamSet;


        // 通过client对象调用SendSms方法发起请求。注意请求方法名与请求对象是对应的
        // 返回的resp是一个SendSmsResponse类的实例，与请求对象对应
        $resp = $client->SendSms($req);

        // 输出json格式的字符串回包
        return json_decode($resp->toJsonString(), true);

        // 也可以取出单个值。
        // 您可以通过官网接口文档或跳转到response对象的定义处查看返回字段的定义
//        print_r($resp->TotalCount);

    }
}
