class AddRememberTokensToUsers < ActiveRecord::Migration
  def change
    add_column :students, :remember_token, :string
    add_index  :students, :remember_token
    
    add_column :teachers, :remember_token, :string
    add_index  :teachers, :remember_token
  end
end
