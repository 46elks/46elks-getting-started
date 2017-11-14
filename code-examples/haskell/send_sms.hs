--
-- 46elks API Sample
-- Sending SMS using Haskell and the 46elks API
--
{-# LANGUAGE OverloadedStrings #-}

import Data.Aeson (FromJSON, parseJSON, withObject, (.:))
import Data.ByteString (ByteString)
import Data.ByteString.Char8 (pack)
import Data.Monoid ((<>))
import Network.HTTP.Simple ( Request, parseRequest, httpJSON
                           , setRequestBasicAuth, setRequestBodyURLEncoded
                           , setRequestMethod, getResponseBody)

-- | SMS data type for 46elks API, the compiler will automatically
-- derive instances for transforming an SMS to a JSON object.
data SMS = SMS { smsTo :: String
               , smsFrom :: String
               , smsMessage :: String
               } deriving (Show)

type Username = ByteString
type Secret   = ByteString

-- | How to parse the status of a sent SMS from a 46elks JSON response
instance FromJSON SMS where
  parseJSON = withObject "status" $ \o ->
    SMS <$> o  .: "to" <*> o .: "from" <*> o .: "message"

-- | Prepares a SMS for inclusion in a URLEncoded POST body.
urlEncodeSMS :: SMS -> [(ByteString, ByteString)]
urlEncodeSMS (SMS to from message) = [ ("to", pack to)
                                     , ("from", pack from)
                                     , ("message", pack message) ]

-- Your 46elks API key
username :: Username
username = "YOUR_API_USER_IDENTIFIER"

-- Your 46elks API secret
secret :: Secret
secret = "YOUR_API_SECRET"

-- Format the API URL to use for the request
apiUrl :: String
apiUrl = "https://api.46elks.com/a1/SMS"

-- | Prepares a request by making it a POST request, with JSON
-- encoding and Basic authentication.
prepareRequest :: SMS -> Request -> Request
prepareRequest sms req =
  setRequestBodyURLEncoded (urlEncodeSMS sms)
  . setRequestMethod "POST"
  . setRequestBasicAuth username secret
  $ req

-- | Sends a sms to a number through 46elks api.
send_sms :: IO ()
send_sms = do
    -- Send SMS POST to 46elks
    let sms = SMS "+46700000000" "Haskelk" "Hello from Haskell"
    request <- prepareRequest sms <$> parseRequest apiUrl

    -- Parse the result for pretty printing
    body <- getResponseBody <$> httpJSON request

    -- Print response message
    putStrLn $ responseString (smsMessage body) (smsFrom body) (smsTo body)

      where responseString :: String -> String -> String -> String
            responseString msg sender recipient =
              "Sent \"" <> msg <> "\" from " <> sender <> " to " <> recipient

main :: IO ()
main = send_sms
