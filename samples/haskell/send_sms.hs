--
-- 46elks API Sample
-- Sending SMS using Haskell and the 46elks API
--
{-# LANGUAGE OverloadedStrings #-}
import Data.Aeson ((.:), decode, FromJSON(..), Value(..))
import Data.ByteString (ByteString)
import Data.HashMap.Strict as HM
import Network.HTTP.Conduit (newManager
                            , tlsManagerSettings
                            , parseUrl
                            , httpLbs
                            , urlEncodedBody
                            , responseBody)
import Text.Printf (printf)

-- Information about how to porse the response code from 46elks API
data Status = Status { from :: String
                     , to :: String
                     , message :: String
                     } deriving (Show)

-- Parse response code from the 46elks API into a Status object
instance FromJSON Status where
    parseJSON (Object v) =
        Status <$>
        (v .: "from") <*>
        (v .: "to") <*>
        (v .: "message")

-- Your 46elks API key
username :: String
username = "YOUR_API_USER_IDENTIFIER"

-- Your 46elks API secret
secret :: String
secret = "YOUR_API_SECRET"

-- Format the API URL to use for the request
apiurl :: String
apiurl = printf "https://%s:%s@api.46elks.com/a1/SMS" username secret

prepare_sms :: ByteString -> ByteString -> ByteString -> [(ByteString, ByteString)]
prepare_sms to from message =
    [("to", to), ("from", from), ("message", message)]

send_sms :: IO ()
send_sms = do
    -- Send SMS POST to 46elks
    let sms_content = prepare_sms "+46700000000" "Haskelk" "Hello from Haskell"
    manager <- newManager tlsManagerSettings
    request' <- parseUrl apiurl
    let request = urlEncodedBody sms_content request'
    result <- httpLbs request manager
    -- Parse the result for pretty printing
    let resultStr = responseBody result
    let (Just (Object json)) = decode resultStr :: Maybe Value
    let (Just (String from)) = HM.lookup "from" json
    let (Just (String to)) = HM.lookup "to" json
    let (Just (String message)) = HM.lookup "message" json
    -- Pretty print
    printf "Sent \"%s\" from %s to %s\n" message from to

main :: IO ()
main = send_sms

