# Rudimentary HTTPS POST request using MicroPython without any dependencies.
# Supports Basic Auth and encodes data as x-www-form-urlencoded.
#
# Written by Johannes Ridderstedt <johannesl@46elks.com>
# This code is public domain. Use freely.

from ubinascii import b2a_base64
import usocket
import ussl

# API credidentials
username = 'u2c11ef65b429a8e16ccb1f960d02c734'
password = 'C0ACCEEC0FAFE879189DD5D57F6EC348'

def quote( value ):
  l = []
  for ch in value.encode( 'utf-8' ):
    if ch == b' ':
      l.append( b'+' )
    elif ch > 32 and ch < 128 and ch not in b'?=':
      l.append( b'%c' % ch )
    else:
      l.append( b'%%%02X' % ch )
  return b''.join( l )

def api_post( path, data ):

  info = usocket.getaddrinfo( 'api.46elks.com', 443 )
  ip = info[0][-1]

  args = []
  for key in data:
    args.append( quote( key ) + '=' + quote( data[key] ) )
  content = b'&'.join( args )

  lines = [
    b'POST /a1/%s HTTP/1.0' % path,
    b'Authorization: Basic %s' % b2a_base64( username + ':' + password )[:-1],
    b'Content-type: application/x-www-form-urlencoded',
    b'Content-Length: %d' % len( content ),
    b'',
    content
  ]

  conn = usocket.socket()
  conn.connect( ip )
  conn = ussl.wrap_socket( conn )
  conn.write( b'\r\n'.join( lines ) )
  print( conn.read(4096).decode('utf-8') )
  conn.close()

sms = {
  'to': '+46704508449',
  'from': 'MicroPython',
  'message': 'Hello from MicroPython!'
}
api_post( '/sms', sms )
