import { APP_BASE_HOST, APP_VERSION } from '../../../config/constants'
import type { ApiEnvelope } from './authService'

const BASE_USERS = `${APP_BASE_HOST}/api/${APP_VERSION.toLowerCase()}/user-users`
const BASE_USER_DATA = `${APP_BASE_HOST}/api/${APP_VERSION.toLowerCase()}/user-user-data`

export interface CreateUserUsersPayload {
  username: string
  email: string
  password_hash: string
}

export interface CreateUserUserDataPayload {
  user_id: string
  full_name: string
  cpf?: string
  phone?: string
  birth_date?: string
  address_zipcode?: string
  address_street?: string
}

async function postPublic<T>(url: string, body: unknown): Promise<ApiEnvelope<T>> {
  const response = await fetch(url, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(body),
  })
  return response.json() as Promise<ApiEnvelope<T>>
}

export async function createUserUsers(
  payload: CreateUserUsersPayload,
): Promise<ApiEnvelope<{ id: string }>> {
  return postPublic(`${BASE_USERS}/create`, payload)
}

export async function createUserUserData(
  payload: CreateUserUserDataPayload,
): Promise<ApiEnvelope<{ id: string }>> {
  return postPublic(`${BASE_USER_DATA}/create`, payload)
}
